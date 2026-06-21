<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Archivo;
use App\Models\Nivel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now        = Carbon::now();
        $mesActual  = $now->month;
        $anioActual = $now->year;

        // ── KPIs ────────────────────────────────────────────────────────────
        $totalDocentes   = User::where('rol','docente')->where('activo',true)->count();
        $totalArchivos   = Archivo::count();
        $archivosEsteAnio = Archivo::whereYear('created_at', $anioActual)->count();

        // Promedio de archivos por docente
        $promedioXDocente = $totalDocentes > 0
            ? round($totalArchivos / $totalDocentes, 1)
            : 0;

        // ── Archivos por bimestre (gráfico de barras) ───────────────────────
        $porBimestre  = Archivo::select('bimestre', DB::raw('count(*) as total'))
                               ->whereNotNull('bimestre')
                               ->groupBy('bimestre')
                               ->orderBy('bimestre')
                               ->pluck('total','bimestre')
                               ->toArray();
        $bimestreData = [
            $porBimestre[1] ?? 0,
            $porBimestre[2] ?? 0,
            $porBimestre[3] ?? 0,
            $porBimestre[4] ?? 0,
        ];

        // ── Archivos por nivel (dona) ───────────────────────────────────────
        $archivosPorNivel = Nivel::with(['grados' => fn($q) => $q->withCount('archivos')])->get();
        $nivelLabels = $archivosPorNivel->pluck('nombre')->toArray();
        $nivelData   = $archivosPorNivel->map(fn($n) => $n->grados->sum('archivos_count'))->toArray();

        // ── Archivos por tipo ───────────────────────────────────────────────
        $porTipo = Archivo::select('tipo_archivo', DB::raw('count(*) as total'))
                          ->groupBy('tipo_archivo')
                          ->get()
                          ->mapWithKeys(function($r) {
                              $label = match(true) {
                                  str_contains($r->tipo_archivo,'pdf')             => 'PDF',
                                  str_contains($r->tipo_archivo,'word')
                                   || str_contains($r->tipo_archivo,'document')    => 'Word',
                                  str_contains($r->tipo_archivo,'sheet')
                                   || str_contains($r->tipo_archivo,'excel')       => 'Excel',
                                  str_contains($r->tipo_archivo,'presentation')
                                   || str_contains($r->tipo_archivo,'powerpoint')  => 'PPT',
                                  default => 'Otro',
                              };
                              return [$label => $r->total];
                          })
                          ->toArray();

        // ── Top 10 docentes por archivos (con scroll en vista) ──────────────
        $topDocentes = User::where('rol','docente')
                           ->withCount('archivos')
                           ->orderByDesc('archivos_count')
                           ->take(10)
                           ->get();

        // ── Últimos 8 archivos ──────────────────────────────────────────────
        $ultimosArchivos = Archivo::with(['docente','curso','grado','seccion'])
                                  ->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalDocentes','totalArchivos','archivosEsteAnio',
            'promedioXDocente',
            'bimestreData','nivelLabels','nivelData',
            'porTipo','topDocentes','archivosPorNivel','ultimosArchivos'
        ));
    }
}
