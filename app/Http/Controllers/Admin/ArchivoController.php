<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Archivo;
use App\Models\User;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Controlador para visualizar y gestionar los archivos subidos por los docentes.
 * Solo accesible para administradores.
 */
class ArchivoController extends Controller
{
    /**
     * Lista todos los archivos con filtros opcionales.
     * Filtros: docente, curso, grado, sección, nivel, rango de fechas.
     */
    public function index(Request $request)
    {
        // Construcción de la consulta base con relaciones necesarias
        $query = Archivo::with(['docente', 'curso', 'grado.nivel', 'seccion'])
                        ->latest();

        // ── Aplicar filtros opcionales ────────────────────────────

        // Filtrar por docente
        if ($request->filled('docente_id')) {
            $query->where('user_id', $request->docente_id);
        }

        // Filtrar por bimestre (excluyendo el "0" que representa todos los bimestres)
        if ($request->filled('bimestre') && $request->bimestre != '0') {
            $query->where('bimestre', $request->bimestre);
        }

        // Filtrar por curso (por ID o por Nombre)
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        } elseif ($request->filled('curso_nombre')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->curso_nombre . '%');
            });
        }

        // Filtrar por grado
        if ($request->filled('grado_id')) {
            $query->where('grado_id', $request->grado_id);
        }

        // Filtrar por sección
        if ($request->filled('seccion_id')) {
            $query->where('seccion_id', $request->seccion_id);
        }

        // Filtrar por nivel (a través del grado)
        if ($request->filled('nivel_id')) {
            $query->whereHas('grado', function ($q) use ($request) {
                $q->where('nivel_id', $request->nivel_id);
            });
        }

        // Filtrar por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtrar por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Filtrar por año lectivo (por defecto muestra el año actual)
        $anioSeleccionado = $request->filled('anio') ? $request->anio : now()->year;
        $query->where('anio', $anioSeleccionado);

        // Filtrar por término de búsqueda general (nombre, descripción, docente o curso)
        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('nombre_original', 'like', '%' . $term . '%')
                  ->orWhere('descripcion', 'like', '%' . $term . '%')
                  ->orWhereHas('docente', function ($sub) use ($term) {
                      $sub->where('nombre', 'like', '%' . $term . '%')
                          ->orWhere('apellido', 'like', '%' . $term . '%')
                          ->orWhere('username', 'like', '%' . $term . '%');
                  })
                  ->orWhereHas('curso', function ($sub) use ($term) {
                      $sub->where('nombre', 'like', '%' . $term . '%');
                  });
            });
        }

        // Detectar si hay algún filtro activo para mostrar resultados
        $hasActiveFilter = $request->filled('docente_id') ||
                           $request->filled('bimestre') ||
                           $request->filled('buscar') ||
                           $request->filled('curso_id') ||
                           $request->filled('curso_nombre') ||
                           $request->filled('grado_id') ||
                           $request->filled('seccion_id') ||
                           $request->filled('nivel_id') ||
                           $request->filled('fecha_desde') ||
                           $request->filled('fecha_hasta');

        if (!$hasActiveFilter) {
            $query->whereNull('id');
        }

        // Paginar resultados (ordenado por bimestre primero)
        $archivos = $query->orderBy('bimestre')->latest()->paginate(20)->withQueryString();

        // Cargar datos para los selectores de filtros
        $docentes  = User::where('rol', 'docente')->orderBy('apellido')->get();
        $cursos    = Curso::orderBy('nombre')->get();
        $grados    = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $secciones = Seccion::orderBy('nombre')->get();
        $niveles   = Nivel::orderBy('nombre')->get();
        $bimestres = \App\Models\Archivo::bimestres();
        $anios     = \App\Models\Archivo::aniosDisponibles();

        return view('admin.archivos.index', compact(
            'archivos',
            'docentes',
            'cursos',
            'grados',
            'secciones',
            'niveles',
            'bimestres',
            'anios',
            'anioSeleccionado'
        ));
    }

    /**
     * Descarga un archivo del almacenamiento.
     * Los archivos se guardan en el disco 'public', no en el disco 'local' por defecto.
     */
    public function download(Archivo $archivo)
    {
        // La ruta guardada en BD es relativa al disco 'public' (ej: docentes/1/doc_xxx.pdf)
        if (!Storage::disk('public')->exists($archivo->ruta)) {
            return redirect()->back()
                             ->with('error', 'El archivo no se encuentra en el servidor.');
        }

        return Storage::disk('public')->download($archivo->ruta, $archivo->nombre_original);
    }

    /**
     * Devuelve el archivo inline para su previsualización en el navegador.
     */
    public function stream(Archivo $archivo)
    {
        if (!Storage::disk('public')->exists($archivo->ruta)) {
            abort(404, 'El archivo no se encuentra en el servidor.');
        }

        return Storage::disk('public')->response($archivo->ruta);
    }

    /**
     * Genera una URL firmada temporal (10 min) para acceder al archivo sin sesión.
     * Útil para Google Docs Viewer que necesita una URL pública accesible.
     */
    public function signedUrl(Archivo $archivo)
    {
        $url = URL::temporarySignedRoute(
            'archivos.public-stream',
            now()->addMinutes(10),
            ['id' => $archivo->id]
        );

        return response()->json(['url' => $url]);
    }

    /**
     * Sirve el archivo usando una URL firmada temporal (sin auth de sesión).
     * Solo accesible con una firma válida y no expirada.
     */
    public function streamPublico(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Enlace expirado o inválido.');
        }

        $archivo = Archivo::findOrFail($id);

        if (!Storage::disk('public')->exists($archivo->ruta)) {
            abort(404, 'El archivo no se encuentra en el servidor.');
        }

        return Storage::disk('public')->response($archivo->ruta);
    }

    /**
     * Elimina lógicamente un archivo (lo envía a la papelera).
     * Requiere confirmación previa desde la vista.
     */
    public function destroy(Archivo $archivo)
    {
        // Eliminar el registro de la base de datos (Soft delete)
        $nombre = $archivo->nombre_original;
        $archivo->delete();

        return redirect()->back()
                         ->with('success', "Archivo \"{$nombre}\" movido a la papelera.");
    }

    /**
     * Muestra la papelera de reciclaje.
     */
    public function papelera(Request $request)
    {
        $archivos = Archivo::onlyTrashed()
                            ->with(['docente', 'curso', 'grado.nivel', 'seccion'])
                            ->latest('deleted_at')
                            ->paginate(20);

        return view('admin.archivos.papelera', compact('archivos'));
    }

    /**
     * Restaura un archivo de la papelera.
     */
    public function restaurar($id)
    {
        $archivo = Archivo::onlyTrashed()->findOrFail($id);
        $archivo->restore();

        return redirect()->route('admin.archivos.papelera')
                         ->with('success', "Archivo \"{$archivo->nombre_original}\" restaurado exitosamente.");
    }

    /**
     * Elimina definitivamente un archivo de la papelera y del disco.
     */
    public function forzarEliminacion($id)
    {
        $archivo = Archivo::onlyTrashed()->findOrFail($id);

        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }

        $nombre = $archivo->nombre_original;
        $archivo->forceDelete();

        return redirect()->route('admin.archivos.papelera')
                         ->with('success', "Archivo \"{$nombre}\" eliminado definitivamente.");
    }
}
