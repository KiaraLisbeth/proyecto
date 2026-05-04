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

        // Filtrar por curso
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
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

        // Paginar resultados
        $archivos = $query->paginate(20)->withQueryString();

        // Cargar datos para los selectores de filtros
        $docentes  = User::where('rol', 'docente')->orderBy('apellido')->get();
        $cursos    = Curso::orderBy('nombre')->get();
        $grados    = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $secciones = Seccion::orderBy('nombre')->get();
        $niveles   = Nivel::orderBy('nombre')->get();

        return view('admin.archivos.index', compact(
            'archivos',
            'docentes',
            'cursos',
            'grados',
            'secciones',
            'niveles'
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
     * Elimina un archivo del sistema (registro DB + archivo físico).
     * Requiere confirmación previa desde la vista.
     */
    public function destroy(Archivo $archivo)
    {
        // Eliminar el archivo físico del disco 'public' si existe
        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }

        // Eliminar el registro de la base de datos
        $nombre = $archivo->nombre_original;
        $archivo->delete();

        return redirect()->back()
                         ->with('success', "Archivo \"{$nombre}\" eliminado exitosamente.");
    }
}
