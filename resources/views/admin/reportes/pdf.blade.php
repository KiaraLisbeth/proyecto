<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Cumplimiento — I.E.P. Esther Carson</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* ── Wrapper centrado con márgenes ──────────────────── */
        .page {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            padding: 0 30px 30px 30px;
        }

        /* ── Encabezado ────────────────────────────────────── */
        .header {
            background: #1e3a8a;
            color: #ffffff;
            padding: 20px 30px;
            margin-bottom: 24px;
            text-align: center;
        }
        .header h1 {
            font-size: 17pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .header h2 {
            font-size: 10.5pt;
            font-weight: 400;
            margin-top: 5px;
            color: #bfdbfe;
        }
        .header-meta {
            margin-top: 7px;
            font-size: 8pt;
            color: #93c5fd;
        }

        /* ── KPIs ──────────────────────────────────────────── */
        table.kpi-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 22px;
            table-layout: fixed;
        }
        .kpi-cell {
            width: 25%;
            border: 1px solid #cbd5e1;
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
            background: #f8fafc;
        }
        .kpi-label {
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.4px;
        }
        .kpi-value {
            font-size: 22pt;
            font-weight: 900;
            color: #1e3a8a;
            line-height: 1.2;
            margin-top: 5px;
        }
        .kpi-value.green { color: #059669; }
        .kpi-value.amber { color: #d97706; }
        .kpi-value.red   { color: #dc2626; }

        /* ── Título de sección ─────────────────────────────── */
        .section-title {
            font-size: 9.5pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #1e3a8a;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 5px;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        /* ── Tabla de reporte ──────────────────────────────── */
        table.reporte {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        /* Anchos fijos para cada columna */
        table.reporte col.col-docente   { width: 36%; }
        table.reporte col.col-num       { width: 16%; }
        table.reporte col.col-pct       { width: 16%; }

        table.reporte thead tr {
            background: #1e3a8a;
            color: #ffffff;
        }
        table.reporte thead th {
            padding: 9px 8px;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            text-align: center;
            vertical-align: middle;
        }
        table.reporte thead th.left {
            text-align: left;
            padding-left: 10px;
        }

        table.reporte tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        table.reporte tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        table.reporte tbody td {
            padding: 9px 8px;
            font-size: 9pt;
            vertical-align: middle;
            text-align: center;
        }
        table.reporte tbody td.left {
            text-align: left;
            padding-left: 10px;
        }

        table.reporte tfoot tr {
            background: #eef2ff;
            border-top: 2px solid #1e3a8a;
        }
        table.reporte tfoot td {
            padding: 9px 8px;
            font-size: 9pt;
            font-weight: 800;
            text-align: center;
            color: #1e3a8a;
        }
        table.reporte tfoot td.left {
            text-align: left;
            padding-left: 10px;
        }

        /* ── Badges ─────────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-red   { background: #fee2e2; color: #991b1b; }

        /* ── Nombre docente ────────────────────────────────── */
        .docente-nombre {
            font-weight: 700;
            color: #1e293b;
            font-size: 9pt;
        }
        .docente-username {
            font-size: 7pt;
            color: #94a3b8;
            font-family: monospace;
            margin-top: 1px;
        }

        /* ── Pie de página ─────────────────────────────────── */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Encabezado institucional --}}
    <div class="header">
        <h1>I.E.P. Esther Carson</h1>
        <h2>{{ $tituloReporte }}</h2>
        <div class="header-meta">
            Generado el {{ now()->format('d/m/Y \a \l\a\s H:i A') }}
        </div>
    </div>

    <div class="page">

        {{-- KPIs --}}
        @php
            $porcColor = $porcentajeGlobal >= 90 ? 'green' : ($porcentajeGlobal >= 50 ? 'amber' : 'red');
        @endphp
        <table class="kpi-grid">
            <tr>
                <td class="kpi-cell">
                    <div class="kpi-label">Cumplimiento Global</div>
                    <div class="kpi-value {{ $porcColor }}">{{ $porcentajeGlobal }}%</div>
                </td>
                <td class="kpi-cell">
                    <div class="kpi-label">Docentes Evaluados</div>
                    <div class="kpi-value">{{ $docentes->count() }}</div>
                </td>
                <td class="kpi-cell">
                    <div class="kpi-label">Asignaciones Totales</div>
                    <div class="kpi-value">{{ $totalAsignacionesGlobal }}</div>
                </td>
                <td class="kpi-cell">
                    <div class="kpi-label">Documentos Subidos</div>
                    <div class="kpi-value">{{ $totalArchivosSubidos }}</div>
                </td>
            </tr>
        </table>

        {{-- Tabla de cumplimiento --}}
        <div class="section-title">Estado de Carga por Docente</div>

        <table class="reporte">
            <colgroup>
                <col class="col-docente">
                <col class="col-num">
                <col class="col-num">
                <col class="col-num">
                <col class="col-pct">
            </colgroup>
            <thead>
                <tr>
                    <th class="left">Docente</th>
                    <th>Cursos Dictados</th>
                    <th>Completados</th>
                    <th>Pendientes</th>
                    <th>% Cumplimiento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reporte as $item)
                    @php
                        $p = $item['porcentaje'];
                        $badgeClass = $p >= 90 ? 'badge-green' : ($p >= 50 ? 'badge-amber' : 'badge-red');
                    @endphp
                    <tr>
                        <td class="left">
                            <div class="docente-nombre">{{ $item['docente']->nombre_completo }}</div>
                            <div class="docente-username">{{ $item['docente']->username }}</div>
                        </td>
                        <td>{{ $item['total_asignaciones'] }}</td>
                        <td>{{ $item['completadas'] }}</td>
                        <td>{{ $item['pendientes'] }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $p }}%</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#94a3b8; padding: 20px;">
                            No hay docentes registrados o activos en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td class="left">TOTAL GENERAL</td>
                    <td>{{ $totalAsignacionesGlobal }}</td>
                    <td>{{ $totalCompletadasGlobal }}</td>
                    <td>{{ $totalAsignacionesGlobal - $totalCompletadasGlobal }}</td>
                    <td>{{ $porcentajeGlobal }}%</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            I.E.P. Esther Carson &mdash; Sistema de Gestión Docente &mdash; {{ now()->format('Y') }}
        </div>

    </div>

</body>
</html>
