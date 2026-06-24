<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador para generar reportes del estado de cumplimiento
 * en la subida de documentos por parte de los docentes.
 */
class ReporteController extends Controller
{
    /**
     * Muestra la pantalla principal de reportes.
     * Cuando bimestre=0 muestra el consolidado de los 4 bimestres.
     */
    public function index(Request $request)
    {
        $anio     = $request->filled('anio')     ? (int)$request->anio     : now()->year;
        $bimestre = $request->filled('bimestre') ? (int)$request->bimestre : 1;
        $generado = $request->boolean('generar');

        $anios     = Archivo::aniosDisponibles();
        $bimestres = Archivo::bimestres();

        // Sin reporte generado → vista vacía
        if (!$generado) {
            return view('admin.reportes.index', [
                'reporte'                 => [],
                'totalAsignacionesGlobal' => 0,
                'totalCompletadasGlobal'  => 0,
                'porcentajeGlobal'        => 0,
                'totalArchivosSubidos'    => 0,
                'docentes'                => collect(),
                'anio'                    => $anio,
                'bimestre'                => $bimestre,
                'anios'                   => $anios,
                'bimestres'               => $bimestres,
                'generado'                => false,
            ]);
        }

        $todosBimestres   = ($bimestre === 0);
        $bimestresEvaluar = $todosBimestres ? [1, 2, 3, 4] : [$bimestre];

        $docentes = User::where('rol', 'docente')
                        ->where('activo', 1)
                        ->with(['asignaciones.curso', 'asignaciones.grado.nivel', 'asignaciones.seccion'])
                        ->get();

        // Cargar archivos del año (todos o solo el bimestre elegido)
        $queryArchivos = Archivo::where('anio', $anio);
        if (!$todosBimestres) {
            $queryArchivos->where('bimestre', $bimestre);
        }

        $archivos = $queryArchivos->get()->groupBy(function ($item) use ($todosBimestres) {
            $base = $item->user_id . '-' . $item->curso_id . '-' . $item->grado_id . '-' . $item->seccion_id;
            return $todosBimestres ? $base . '-' . $item->bimestre : $base;
        });

        $reporte                 = [];
        $totalAsignacionesGlobal = 0;
        $totalCompletadasGlobal  = 0;

        foreach ($docentes as $docente) {
            $asignaciones        = $docente->asignaciones;
            // «Cursos dictados» = número real de asignaciones (nunca se multiplica por bimestres)
            $totalAsig           = $asignaciones->count();
            $completadas         = 0;
            $detalleAsignaciones = [];

            foreach ($asignaciones as $asig) {
                // En modo anual: el curso se cuenta como completado solo si tiene
                // archivo en TODOS los bimestres evaluados.
                $bimestresConArchivo = 0;

                foreach ($bimestresEvaluar as $bim) {
                    $key = $todosBimestres
                        ? $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id . '-' . $bim
                        : $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id;

                    $archivosSubidos = $archivos->get($key) ?? collect();
                    $cumpleBim       = $archivosSubidos->isNotEmpty();

                    if ($cumpleBim) $bimestresConArchivo++;

                    $detalleAsignaciones[] = [
                        'curso'          => $asig->curso->nombre ?? '—',
                        'grado'          => ($asig->grado->nombre ?? '—') . ' Sec. ' . ($asig->seccion->nombre ?? '—'),
                        'nivel'          => $asig->grado->nivel->nombre ?? '—',
                        'bimestre'       => $todosBimestres ? ($bimestres[$bim] ?? $bim) : null,
                        'archivos_count' => $archivosSubidos->count(),
                        'cumplido'       => $cumpleBim,
                        'archivos'       => $archivosSubidos,
                    ];
                }

                // En modo un solo bimestre: completado = tiene al menos 1 archivo.
                // En modo anual: completado = tiene archivo en todos los bimestres.
                $cumpleAsig = $todosBimestres
                    ? ($bimestresConArchivo === count($bimestresEvaluar))
                    : ($bimestresConArchivo > 0);

                if ($cumpleAsig) $completadas++;
            }

            $totalAsignacionesGlobal += $totalAsig;
            $totalCompletadasGlobal  += $completadas;

            $reporte[] = [
                'docente'            => $docente,
                'total_asignaciones' => $totalAsig,
                'completadas'        => $completadas,
                'pendientes'         => $totalAsig - $completadas,
                'porcentaje'         => $totalAsig > 0 ? round(($completadas / $totalAsig) * 100) : 100,
                'detalle'            => $detalleAsignaciones,
            ];
        }

        $porcentajeGlobal     = $totalAsignacionesGlobal > 0
            ? round(($totalCompletadasGlobal / $totalAsignacionesGlobal) * 100)
            : 100;

        $totalArchivosSubidos = $todosBimestres
            ? Archivo::where('anio', $anio)->count()
            : Archivo::where('anio', $anio)->where('bimestre', $bimestre)->count();

        return view('admin.reportes.index', compact(
            'reporte', 'totalAsignacionesGlobal', 'totalCompletadasGlobal',
            'porcentajeGlobal', 'totalArchivosSubidos', 'docentes',
            'anio', 'bimestre', 'anios', 'bimestres', 'generado'
        ));
    }

    /**
     * Exporta el reporte a PDF descargable.
     * Usa barryvdh/laravel-dompdf para generar el archivo.
     */
    public function exportarPdf(Request $request)
    {
        $anio     = $request->filled('anio')     ? (int)$request->anio     : now()->year;
        $bimestre = $request->filled('bimestre') ? (int)$request->bimestre : 1;

        $todosBimestres   = ($bimestre === 0);
        $bimestresEvaluar = $todosBimestres ? [1, 2, 3, 4] : [$bimestre];
        $bimestres        = Archivo::bimestres();

        $tituloReporte = $todosBimestres
            ? "Reporte Anual Consolidado — Todos los Bimestres ({$anio})"
            : 'Reporte de Cumplimiento — ' . ($bimestres[$bimestre] ?? $bimestre) . " ({$anio})";

        $nombreArchivo = $todosBimestres
            ? "Reporte_Cumplimiento_{$anio}_Todos_Bimestres.pdf"
            : "Reporte_Cumplimiento_{$anio}_Bimestre_{$bimestre}.pdf";

        $docentes = User::where('rol', 'docente')
                        ->where('activo', 1)
                        ->with(['asignaciones.curso', 'asignaciones.grado.nivel', 'asignaciones.seccion'])
                        ->get();

        $queryArchivos = Archivo::where('anio', $anio);
        if (!$todosBimestres) {
            $queryArchivos->where('bimestre', $bimestre);
        }

        $archivos = $queryArchivos->get()->groupBy(function ($item) use ($todosBimestres) {
            $base = $item->user_id . '-' . $item->curso_id . '-' . $item->grado_id . '-' . $item->seccion_id;
            return $todosBimestres ? $base . '-' . $item->bimestre : $base;
        });

        $reporte                 = [];
        $totalAsignacionesGlobal = 0;
        $totalCompletadasGlobal  = 0;

        foreach ($docentes as $docente) {
            $asignaciones        = $docente->asignaciones;
            // «Cursos dictados» = número real de asignaciones (nunca se multiplica por bimestres)
            $totalAsig           = $asignaciones->count();
            $completadas         = 0;
            $detalleAsignaciones = [];

            foreach ($asignaciones as $asig) {
                $bimestresConArchivo = 0;

                foreach ($bimestresEvaluar as $bim) {
                    $key = $todosBimestres
                        ? $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id . '-' . $bim
                        : $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id;

                    $archivosSubidos = $archivos->get($key) ?? collect();
                    $cumpleBim       = $archivosSubidos->isNotEmpty();

                    if ($cumpleBim) $bimestresConArchivo++;

                    $detalleAsignaciones[] = [
                        'curso'          => $asig->curso->nombre ?? '—',
                        'grado'          => ($asig->grado->nombre ?? '—') . ' Sec. ' . ($asig->seccion->nombre ?? '—'),
                        'nivel'          => $asig->grado->nivel->nombre ?? '—',
                        'bimestre'       => $todosBimestres ? ($bimestres[$bim] ?? $bim) : null,
                        'archivos_count' => $archivosSubidos->count(),
                        'cumplido'       => $cumpleBim,
                    ];
                }

                $cumpleAsig = $todosBimestres
                    ? ($bimestresConArchivo === count($bimestresEvaluar))
                    : ($bimestresConArchivo > 0);

                if ($cumpleAsig) $completadas++;
            }

            $totalAsignacionesGlobal += $totalAsig;
            $totalCompletadasGlobal  += $completadas;

            $reporte[] = [
                'docente'            => $docente,
                'total_asignaciones' => $totalAsig,
                'completadas'        => $completadas,
                'pendientes'         => $totalAsig - $completadas,
                'porcentaje'         => $totalAsig > 0 ? round(($completadas / $totalAsig) * 100) : 100,
            ];
        }

        $porcentajeGlobal     = $totalAsignacionesGlobal > 0
            ? round(($totalCompletadasGlobal / $totalAsignacionesGlobal) * 100)
            : 100;

        $totalArchivosSubidos = $todosBimestres
            ? Archivo::where('anio', $anio)->count()
            : Archivo::where('anio', $anio)->where('bimestre', $bimestre)->count();

        $pdf = Pdf::loadView('admin.reportes.pdf', compact(
            'reporte', 'totalAsignacionesGlobal', 'totalCompletadasGlobal',
            'porcentajeGlobal', 'totalArchivosSubidos', 'docentes',
            'anio', 'bimestre', 'bimestres', 'tituloReporte'
        ))->setPaper('a4', 'portrait');

        return $pdf->download($nombreArchivo);
    }

    /**
     * Exporta el reporte a CSV.
     * Si bimestre=0 incluye una fila por docente por cada bimestre.
     */
    public function exportarCsv(Request $request)
    {
        $anio     = $request->filled('anio')     ? (int)$request->anio     : now()->year;
        $bimestre = $request->filled('bimestre') ? (int)$request->bimestre : 1;

        $todosBimestres   = ($bimestre === 0);
        $bimestresEvaluar = $todosBimestres ? [1, 2, 3, 4] : [$bimestre];
        $bimestres        = Archivo::bimestres();

        $nombreArchivo = $todosBimestres
            ? "Reporte_Cumplimiento_{$anio}_Todos_Bimestres.csv"
            : "Reporte_Cumplimiento_{$anio}_Bimestre_{$bimestre}.csv";

        $tituloReporte = $todosBimestres
            ? "Reporte Anual Consolidado — Todos los Bimestres ({$anio})"
            : "Reporte de Cumplimiento — " . ($bimestres[$bimestre] ?? $bimestre) . " ({$anio})";

        $docentes = User::where('rol', 'docente')
                        ->where('activo', 1)
                        ->with(['asignaciones.curso', 'asignaciones.grado', 'asignaciones.seccion'])
                        ->get();

        $queryArchivos = Archivo::where('anio', $anio);
        if (!$todosBimestres) $queryArchivos->where('bimestre', $bimestre);
        $archivosPorBimestre = $queryArchivos->get()->groupBy('bimestre');

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$nombreArchivo}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($docentes, $archivosPorBimestre, $anio, $bimestres, $bimestresEvaluar, $todosBimestres, $tituloReporte) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            // Cabecera principal del reporte
            fputcsv($file, ['I.E.P. Esther Carson'], ';');
            fputcsv($file, [$tituloReporte], ';');
            fputcsv($file, [], ';');

            foreach ($bimestresEvaluar as $bim) {
                $nombreBim   = $bimestres[$bim] ?? "Bimestre {$bim}";
                $archivosBim = $archivosPorBimestre->get($bim, collect())
                    ->groupBy(fn ($a) => $a->user_id . '-' . $a->curso_id . '-' . $a->grado_id . '-' . $a->seccion_id);

                $repBim           = [];
                $totalAsigBim     = 0;
                $totalCompBim     = 0;
                $totalArchivosBim = $archivosPorBimestre->get($bim, collect())->count();

                foreach ($docentes as $docente) {
                    $asignaciones = $docente->asignaciones;
                    $totalAsig    = $asignaciones->count();
                    $completadas  = 0;

                    foreach ($asignaciones as $asig) {
                        $key = $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id;
                        if ($archivosBim->has($key)) $completadas++;
                    }

                    $totalAsigBim += $totalAsig;
                    $totalCompBim += $completadas;

                    $repBim[] = [
                        'docente'            => $docente->nombre_completo,
                        'total_asignaciones' => $totalAsig,
                        'completadas'        => $completadas,
                        'pendientes'         => $totalAsig - $completadas,
                        'porcentaje'         => $totalAsig > 0 ? round(($completadas / $totalAsig) * 100) : 100,
                    ];
                }

                $porcGlobal = $totalAsigBim > 0 ? round(($totalCompBim / $totalAsigBim) * 100) : 100;

                // Título de la sección si son todos los bimestres
                if ($todosBimestres) {
                    fputcsv($file, ["📋 {$nombreBim}"], ';');
                }

                // Resumen de cumplimiento global
                fputcsv($file, ['Cumplimiento Global', $porcGlobal . '%'], ';');
                fputcsv($file, ['Documentos Subidos', $totalArchivosBim], ';');
                fputcsv($file, [], ';');

                // Encabezados de tabla
                fputcsv($file, [
                    'Docente', 'Cursos Dictados', 'Completados', 'Pendientes', '% Cumplimiento',
                ], ';');

                // Filas de datos
                foreach ($repBim as $item) {
                    fputcsv($file, [
                        $item['docente'],
                        $item['total_asignaciones'],
                        $item['completadas'],
                        $item['pendientes'],
                        $item['porcentaje'] . '%',
                    ], ';');
                }

                // Espacio en blanco al finalizar el bimestre
                fputcsv($file, [], ';');
                fputcsv($file, [], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporta el reporte a Word (.doc).
     * Si bimestre=0 genera una sección por cada bimestre.
     */
    public function exportarWord(Request $request)
    {
        $anio     = $request->filled('anio')     ? (int)$request->anio     : now()->year;
        $bimestre = $request->filled('bimestre') ? (int)$request->bimestre : 1;

        $todosBimestres   = ($bimestre === 0);
        $bimestresEvaluar = $todosBimestres ? [1, 2, 3, 4] : [$bimestre];
        $bimestres        = Archivo::bimestres();

        $nombreArchivo = $todosBimestres
            ? "Reporte_Cumplimiento_{$anio}_Todos_Bimestres.doc"
            : "Reporte_Cumplimiento_{$anio}_Bimestre_{$bimestre}.doc";

        $tituloReporte = $todosBimestres
            ? "Reporte Anual Consolidado — Todos los Bimestres ({$anio})"
            : "Reporte de Cumplimiento — " . ($bimestres[$bimestre] ?? $bimestre) . " ({$anio})";

        $docentes = User::where('rol', 'docente')
                        ->where('activo', 1)
                        ->with(['asignaciones.curso', 'asignaciones.grado.nivel', 'asignaciones.seccion'])
                        ->get();

        $queryArchivos = Archivo::where('anio', $anio);
        if (!$todosBimestres) $queryArchivos->where('bimestre', $bimestre);
        $archivosPorBimestre = $queryArchivos->get()->groupBy('bimestre');

        $css = "
            body  { font-family: Arial, sans-serif; font-size: 11pt; color: #333; }
            h1    { font-size: 18pt; text-align: center; color: #1e3b8a; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
            h2    { font-size: 13pt; text-align: center; color: #475569; margin-top: 0; margin-bottom: 18px; }
            h3    { font-size: 12pt; color: #1e3b8a; border-bottom: 2px solid #cbd5e1; padding-bottom: 4px; margin-top: 28px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 18px; }
            th    { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 8px; font-size: 10pt; font-weight: bold; color: #475569; text-transform: uppercase; }
            td    { border: 1px solid #cbd5e1; padding: 8px; font-size: 10pt; }
            .c    { text-align: center; }
            .b    { font-weight: bold; }
            .stats{ border: 1px solid #cbd5e1; padding: 12px; margin-bottom: 14px; background-color: #f8fafc; }
            .lbl  { font-size: 9pt; text-transform: uppercase; font-weight: bold; color: #64748b; }
            .val  { font-size: 18pt; font-weight: bold; color: #1e3a8a; }";

        $html  = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>";
        $html .= "<head><meta charset='utf-8'><title>Reporte</title><style>{$css}</style></head><body>";
        $html .= "<h1>I.E.P. Esther Carson</h1><h2>{$tituloReporte}</h2>";

        foreach ($bimestresEvaluar as $bim) {
            $nombreBim   = $bimestres[$bim] ?? "Bimestre {$bim}";
            $archivosBim = $archivosPorBimestre->get($bim, collect())
                ->groupBy(fn ($a) => $a->user_id . '-' . $a->curso_id . '-' . $a->grado_id . '-' . $a->seccion_id);

            $repBim           = [];
            $totalAsigBim     = 0;
            $totalCompBim     = 0;
            $totalArchivosBim = $archivosPorBimestre->get($bim, collect())->count();

            foreach ($docentes as $docente) {
                $asignaciones = $docente->asignaciones;
                $totalAsig    = $asignaciones->count();
                $completadas  = 0;

                foreach ($asignaciones as $asig) {
                    $key = $docente->id . '-' . $asig->curso_id . '-' . $asig->grado_id . '-' . $asig->seccion_id;
                    if ($archivosBim->has($key)) $completadas++;
                }

                $totalAsigBim += $totalAsig;
                $totalCompBim += $completadas;

                $repBim[] = [
                    'docente'            => $docente,
                    'total_asignaciones' => $totalAsig,
                    'completadas'        => $completadas,
                    'pendientes'         => $totalAsig - $completadas,
                    'porcentaje'         => $totalAsig > 0 ? round(($completadas / $totalAsig) * 100) : 100,
                ];
            }

            $porcGlobal = $totalAsigBim > 0 ? round(($totalCompBim / $totalAsigBim) * 100) : 100;

            // Encabezado de sección (solo si son varios bimestres)
            if ($todosBimestres) {
                $html .= "<h3>&#x1F4CB; {$nombreBim}</h3>";
            }

            $html .= "<div class='stats'>
                <table style='border:none;margin:0;width:100%;'>
                <tr style='border:none;'>
                    <td style='border:none;width:50%;'><span class='lbl'>Cumplimiento Global</span><br/><span class='val'>{$porcGlobal}%</span></td>
                    <td style='border:none;width:50%;'><span class='lbl'>Documentos Subidos</span><br/><span class='val'>{$totalArchivosBim}</span></td>
                </tr></table></div>";

            $html .= "<table><thead><tr>
                <th>Docente</th>
                <th class='c'>Cursos Dictados</th>
                <th class='c'>Completados</th>
                <th class='c'>Pendientes</th>
                <th class='c'>% Cumplimiento</th>
            </tr></thead><tbody>";

            foreach ($repBim as $item) {
                $nombre = htmlspecialchars($item['docente']->nombre_completo);
                $html  .= "<tr>
                    <td class='b'>{$nombre}</td>
                    <td class='c'>{$item['total_asignaciones']}</td>
                    <td class='c'>{$item['completadas']}</td>
                    <td class='c'>{$item['pendientes']}</td>
                    <td class='c b'>{$item['porcentaje']}%</td>
                </tr>";
            }

            $html .= "</tbody></table>";
        }

        $html .= "</body></html>";

        return response($html)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename={$nombreArchivo}")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
