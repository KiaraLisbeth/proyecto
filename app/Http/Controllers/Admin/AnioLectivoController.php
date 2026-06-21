<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnioLectivo;
use App\Models\Archivo;
use Illuminate\Http\Request;

/**
 * Controlador para gestionar los Años Lectivos desde el panel Admin.
 */
class AnioLectivoController extends Controller
{
    /**
     * Muestra la página de gestión de años lectivos.
     */
    public function index()
    {
        $anios = AnioLectivo::orderByDesc('anio')->get()->map(function ($a) {
            $a->total_archivos = Archivo::where('anio', $a->anio)->count();

            // Cargar docentes con sus archivos agrupados por bimestre
            $docs = Archivo::with(['docente', 'curso', 'grado.nivel', 'seccion'])
                ->where('anio', $a->anio)
                ->orderBy('bimestre')
                ->orderByDesc('created_at')
                ->get();

            // Agrupar: docente_id → bimestre → archivos
            $a->docentes_data = $docs->groupBy('user_id')->map(function ($docsDocente) {
                return [
                    'docente'    => $docsDocente->first()->docente,
                    'total'      => $docsDocente->count(),
                    'bimestres'  => $docsDocente->groupBy('bimestre')->sortKeys(),
                ];
            })->sortBy(fn($d) => $d['docente']?->apellido);

            return $a;
        });

        return view('admin.anios.index', compact('anios'));
    }

    /**
     * Crea un nuevo año lectivo.
     * El año anterior queda automáticamente archivado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'anio'         => ['required', 'integer', 'min:2026', 'max:2100', 'unique:anio_lectivos,anio'],
            'fecha_inicio' => ['required', 'date'],
        ], [
            'anio.required' => 'El año es obligatorio.',
            'anio.unique'   => 'Ese año lectivo ya existe.',
            'anio.min'      => 'El año mínimo es 2026.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
        ]);

        // Archivar todos los años actuales
        AnioLectivo::where('activo', true)->update(['activo' => false]);

        // Crear el nuevo año como activo
        AnioLectivo::create([
            'anio'         => $request->anio,
            'activo'       => true,
            'fecha_inicio' => $request->fecha_inicio,
        ]);

        return redirect()->route('admin.anios.index')
                         ->with('success', "Año Lectivo {$request->anio} creado y activado exitosamente.");
    }

    /**
     * Activa un año lectivo archivado (y archiva el actual).
     */
    public function activar(AnioLectivo $anio)
    {
        AnioLectivo::where('activo', true)->update(['activo' => false]);
        $anio->update(['activo' => true]);

        return redirect()->route('admin.anios.index')
                         ->with('success', "Año Lectivo {$anio->anio} activado. Los demás quedan archivados.");
    }
}
