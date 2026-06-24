<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StoreArchivoRequest;
use App\Models\Archivo;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Controlador de archivos del Docente.
 * Permite subir, listar, descargar y eliminar archivos propios.
 * Los archivos se guardan en storage/app/public/docentes/{user_id}/
 */
class ArchivoController extends Controller
{
    /**
     * Lista los archivos del docente con filtros opcionales.
     * Incluye búsqueda, filtro por curso y rango de fechas.
     */
    public function index(Request $request)
    {
        $docente = auth()->user();

        $query = $docente->archivos()
                         ->with(['curso', 'grado.nivel', 'seccion'])
                         ->latest();

        // Filtro por bimestre (excluyendo el "0" que representa todos los bimestres)
        if ($request->filled('bimestre') && $request->bimestre != '0') {
            $query->where('bimestre', $request->bimestre);
        }

        // Filtro por término de búsqueda general (nombre o descripción)
        if ($request->filled('buscar')) {
            $term = $request->buscar;
            $query->where(function ($q) use ($term) {
                $q->where('nombre_original', 'like', '%' . $term . '%')
                  ->orWhere('descripcion', 'like', '%' . $term . '%');
            });
        }

        // Filtro por curso (por ID o por Nombre)
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        } elseif ($request->filled('curso_nombre')) {
            $query->whereHas('curso', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->curso_nombre . '%');
            });
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Filtro por año lectivo (por defecto el año actual)
        $anioSeleccionado = $request->filled('anio') ? $request->anio : now()->year;
        $query->where('anio', $anioSeleccionado);

        $archivos = $query->orderBy('bimestre')->latest()->paginate(15)->withQueryString();

        // Cursos del docente para el filtro (solo los que tiene asignados)
        $cursos = Curso::whereHas('asignaciones', function ($q) use ($docente) {
            $q->where('user_id', $docente->id);
        })->orderBy('nombre')->get();

        // Bimestres y años disponibles para los filtros
        $bimestres = \App\Models\Archivo::bimestres();
        $anios     = \App\Models\Archivo::aniosDisponibles();

        return view('docente.archivos.index', compact('archivos', 'cursos', 'bimestres', 'anios', 'anioSeleccionado'));
    }

    /**
     * Muestra el formulario para subir un archivo.
     * El selector de asignación solo carga las asignaciones del docente autenticado.
     */
    public function create()
    {
        $docente = auth()->user();

        // Cargar asignaciones con sus relaciones para el selector
        $asignaciones = $docente->asignaciones()
                                ->with(['curso', 'grado.nivel', 'seccion'])
                                ->get();

        // Bimestres disponibles para el selector (excluyendo el 0 = Todos los Bimestres)
        $bimestres = array_filter(
            \App\Models\Archivo::bimestres(),
            fn($key) => $key > 0,
            ARRAY_FILTER_USE_KEY
        );

        return view('docente.archivos.create', compact('asignaciones', 'bimestres'));
    }

    /**
     * Almacena el archivo subido por el docente.
     * - Genera un nombre único con uniqid para evitar colisiones
     * - Guarda el nombre original en la base de datos
     * - Almacena en storage/app/public/docentes/{user_id}/
     */
    public function store(StoreArchivoRequest $request)
    {
        $docente = auth()->user();

        // Obtener los detalles del archivo subido
        $file = $request->file('archivo');
        $nombreOriginal = $file->getClientOriginalName();

        // Segunda verificación de duplicado (defensa en profundidad, por si la validación
        // del FormRequest fue eludida directamente contra el endpoint)
        $yaExiste = Archivo::withTrashed()
            ->where('user_id', $docente->id)
            ->whereRaw('LOWER(nombre_original) = LOWER(?)', [$nombreOriginal])
            ->exists();

        if ($yaExiste) {
            return redirect()->back()
                             ->withInput()
                             ->withErrors([
                                 'archivo' => "Ya existe un archivo con el nombre \"{$nombreOriginal}\".",
                             ]);
        }

        // Generar nombre único preservando la extensión original
        $extension    = $file->getClientOriginalExtension();
        $nombreUnico  = uniqid('doc_', true) . '.' . $extension;

        // Definir la carpeta de destino por docente para organización
        $carpeta = "docentes/{$docente->id}";

        // Guardar el archivo en storage/app/public/docentes/{user_id}/
        $ruta = $file->storeAs($carpeta, $nombreUnico, 'public');

        // Obtener la asignación seleccionada para extraer curso, grado y sección
        $asignacion = $docente->asignaciones()
                              ->with(['curso', 'grado', 'seccion'])
                              ->findOrFail($request->asignacion_id);

        // Crear el registro en la base de datos
        Archivo::create([
            'user_id'         => $docente->id,
            'nombre_original' => $nombreOriginal,
            'nombre_archivo'  => $nombreUnico,
            'ruta'            => $ruta,
            'tipo_archivo'    => $file->getMimeType(),
            'tamanio'         => $file->getSize(),
            'curso_id'        => $asignacion->curso_id,
            'grado_id'        => $asignacion->grado_id,
            'seccion_id'      => $asignacion->seccion_id,
            'bimestre'        => $request->bimestre,
            'anio'            => now()->year,   // Se guarda automáticamente el año actual
            'descripcion'     => $request->descripcion,
        ]);

        return redirect()->route('docente.archivos.index')
                         ->with('success', "Archivo \"{$nombreOriginal}\" subido exitosamente.");
    }

    /**
     * Descarga un archivo verificando que pertenece al docente autenticado.
     */
    public function download(Archivo $archivo)
    {
        // Seguridad: verificar que el archivo pertenece al docente autenticado
        if ($archivo->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        // La ruta en BD es relativa al disco 'public' (ej: docentes/1/doc_xxx.pdf)
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
        // Seguridad: verificar que el archivo pertenece al docente autenticado
        if ($archivo->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para previsualizar este archivo.');
        }

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
        // Seguridad: verificar que el archivo pertenece al docente autenticado
        if ($archivo->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a este archivo.');
        }

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
     */
    public function destroy(Archivo $archivo)
    {
        // Seguridad: verificar que el archivo pertenece al docente autenticado
        if ($archivo->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este archivo.');
        }

        $nombre = $archivo->nombre_original;
        $archivo->delete(); // Soft delete

        return redirect()->route('docente.archivos.index')
                         ->with('success', "Archivo \"{$nombre}\" movido a la papelera.");
    }

    /**
     * Muestra la papelera de reciclaje.
     */
    public function papelera()
    {
        $docente = auth()->user();
        $archivos = $docente->archivos()
                            ->onlyTrashed()
                            ->with(['curso', 'grado.nivel', 'seccion'])
                            ->latest('deleted_at')
                            ->paginate(15);

        return view('docente.archivos.papelera', compact('archivos'));
    }

    /**
     * Restaura un archivo de la papelera.
     */
    public function restaurar($id)
    {
        $docente = auth()->user();
        $archivo = $docente->archivos()->onlyTrashed()->findOrFail($id);
        $archivo->restore();

        return redirect()->route('docente.archivos.papelera')
                         ->with('success', "Archivo \"{$archivo->nombre_original}\" restaurado exitosamente.");
    }

    /**
     * Elimina definitivamente un archivo de la papelera y del disco.
     */
    public function forzarEliminacion($id)
    {
        $docente = auth()->user();
        $archivo = $docente->archivos()->onlyTrashed()->findOrFail($id);

        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }

        $nombre = $archivo->nombre_original;
        $archivo->forceDelete();

        return redirect()->route('docente.archivos.papelera')
                         ->with('success', "Archivo \"{$nombre}\" eliminado definitivamente.");
    }
}
