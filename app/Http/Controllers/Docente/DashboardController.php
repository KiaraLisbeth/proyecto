<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Archivo;

/**
 * Controlador del Dashboard del Docente.
 * Muestra bienvenida, asignaciones del docente y resumen de archivos propios.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del docente autenticado.
     */
    public function index()
    {
        $docente = auth()->user();

        // Cargar las asignaciones con sus relaciones para mostrar en el dashboard
        $docente->load([
            'asignaciones.curso',
            'asignaciones.grado.nivel',
            'asignaciones.seccion',
        ]);

        // Total de archivos subidos por este docente
        $totalArchivos = $docente->archivos()->count();

        // Los últimos 5 archivos subidos por el docente
        $ultimosArchivos = $docente->archivos()
                                   ->with(['curso', 'grado', 'seccion'])
                                   ->latest()
                                   ->take(5)
                                   ->get();

        return view('docente.dashboard', compact(
            'docente',
            'totalArchivos',
            'ultimosArchivos'
        ));
    }
}
