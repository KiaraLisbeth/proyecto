<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Archivo;
use App\Models\Nivel;

/**
 * Controlador del Dashboard del Administrador.
 * Muestra estadísticas generales del sistema.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el panel principal del administrador con estadísticas.
     */
    public function index()
    {
        // Contar docentes activos (rol=docente y activo=true)
        $totalDocentes = User::where('rol', 'docente')
                             ->where('activo', true)
                             ->count();

        // Total de archivos en el sistema
        $totalArchivos = Archivo::count();

        // Resumen de archivos agrupados por nivel y grado
        // Usamos join para obtener el nivel a través del grado
        $archivosPorNivel = Nivel::with(['grados' => function ($q) {
            $q->withCount('archivos');
        }])->get();

        // Últimos 5 archivos subidos (para actividad reciente)
        $ultimosArchivos = Archivo::with(['docente', 'curso', 'grado', 'seccion'])
                                  ->latest()
                                  ->take(5)
                                  ->get();

        return view('admin.dashboard', compact(
            'totalDocentes',
            'totalArchivos',
            'archivosPorNivel',
            'ultimosArchivos'
        ));
    }
}
