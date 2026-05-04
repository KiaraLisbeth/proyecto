<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StoreArchivoRequest;
use App\Models\Archivo;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Filtro por curso
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        // Filtro por fecha desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $archivos = $query->paginate(15)->withQueryString();

        // Cursos del docente para el filtro (solo los que tiene asignados)
        $cursos = Curso::whereHas('asignaciones', function ($q) use ($docente) {
            $q->where('user_id', $docente->id);
        })->orderBy('nombre')->get();

        return view('docente.archivos.index', compact('archivos', 'cursos'));
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

        return view('docente.archivos.create', compact('asignaciones'));
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
            'user_id'        => $docente->id,
            'nombre_original' => $file->getClientOriginalName(),
            'nombre_archivo'  => $nombreUnico,
            'ruta'            => $ruta,
            'tipo_archivo'    => $file->getMimeType(),
            'tamanio'         => $file->getSize(),
            'curso_id'        => $asignacion->curso_id,
            'grado_id'        => $asignacion->grado_id,
            'seccion_id'      => $asignacion->seccion_id,
            'descripcion'     => $request->descripcion,
        ]);

        return redirect()->route('docente.archivos.index')
                         ->with('success', "Archivo \"{$file->getClientOriginalName()}\" subido exitosamente.");
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
     * Elimina un archivo del sistema y del disco.
     * Solo permite eliminar archivos propios del docente autenticado.
     */
    public function destroy(Archivo $archivo)
    {
        // Seguridad: verificar que el archivo pertenece al docente autenticado
        if ($archivo->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este archivo.');
        }

        // Eliminar el archivo físico del disco 'public'
        if (Storage::disk('public')->exists($archivo->ruta)) {
            Storage::disk('public')->delete($archivo->ruta);
        }

        $nombre = $archivo->nombre_original;
        $archivo->delete();

        return redirect()->route('docente.archivos.index')
                         ->with('success', "Archivo \"{$nombre}\" eliminado correctamente.");
    }
}
