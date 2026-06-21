@extends('layouts.admin')

@section('title', 'Reporte de Cumplimiento')

@section('page-title', 'Reportes de Cumplimiento de Carga')

@section('breadcrumb', 'Admin / Reportes')

@section('topbar-actions')
    <div class="relative inline-block text-left print:hidden" id="downloadDropdown">
        <div>
            <button type="button" onclick="toggleDownloadDropdown(event)" class="btn btn-success btn-sm flex items-center gap-1.5" id="downloadDropdownBtn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Descargar
                <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        <div id="downloadDropdownMenu" class="hidden absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl z-50 py-1.5">
            <button onclick="window.print(); closeDownloadDropdown();" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-500/10 flex items-center gap-2 transition-colors">
                📄 Formato PDF
            </button>
            <a href="{{ route('admin.reportes.exportar', request()->all()) }}" onclick="closeDownloadDropdown();" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-500/10 flex items-center gap-2 transition-colors">
                📊 Formato Excel (CSV)
            </a>
            <a href="{{ route('admin.reportes.exportarWord', request()->all()) }}" onclick="closeDownloadDropdown();" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-500/10 flex items-center gap-2 transition-colors">
                📝 Formato Word (.doc)
            </a>
        </div>
    </div>
@endsection

@section('content')

{{-- Filtros --}}
<div class="card mb-6 print:hidden">
    <div class="card-header flex justify-between items-center">
        <span class="card-title">🔍 Parámetros del Reporte</span>
        @if(request()->hasAny(['anio', 'bimestre']))
            <a href="{{ route('admin.reportes.index') }}" class="text-xs text-slate-500 hover:text-slate-300 font-bold transition-colors">✕ Restaurar por Defecto</a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reportes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="hidden" name="generar" value="1">
            <div>
                <label class="form-label text-slate-400">Año Lectivo</label>
                <select name="anio" class="input py-2 text-xs font-semibold">
                    @foreach($anios as $a => $etiqueta)
                        <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>
                            📅 {{ $a }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-slate-400">Bimestre Escolar</label>
                <select name="bimestre" class="input py-2 text-xs font-semibold">
                    @foreach($bimestres as $num => $nombre)
                        <option value="{{ $num }}" {{ $bimestre == $num ? 'selected' : '' }}>
                            🎯 {{ $nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full btn btn-primary py-2.5">
                    ⚙️ Generar Reporte
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Título Impreso (Solo visible al imprimir) --}}
<div class="hidden print:block mb-8 text-center border-b pb-4">
    <h1 class="text-2xl font-black uppercase text-slate-800">I.E.P. Esther Carson</h1>
    <h2 class="text-lg font-bold text-slate-600 mt-1">Reporte de Cumplimiento de Carga de Documentos</h2>
    <p class="text-xs text-slate-500 mt-2">
        <strong>Bimestre:</strong> {{ $bimestres[$bimestre] ?? $bimestre }} | 
        <strong>Año Lectivo:</strong> {{ $anio }} | 
        <strong>Fecha de Reporte:</strong> {{ now()->format('d/m/Y H:i A') }}
    </p>
</div>

{{-- Resultados: solo si se generó el reporte --}}
@if($generado)

{{-- Grid de KPIs / Estadísticas --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- KPI: Cumplimiento --}}
    @php
        $bgColor = match(true) {
            $porcentajeGlobal >= 90 => 'from-emerald-500/20 to-teal-500/5 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
            $porcentajeGlobal >= 50 => 'from-amber-500/20 to-yellow-500/5 text-amber-600 dark:text-amber-400 border-amber-500/30',
            default => 'from-rose-500/20 to-red-500/5 text-rose-600 dark:text-rose-400 border-rose-500/30'
        };
    @endphp
    <div class="card bg-gradient-to-br {{ $bgColor }} border flex flex-col justify-between p-5 relative overflow-hidden group hover:scale-[1.01] transition-transform">
        <div>
            <p class="text-xs uppercase font-extrabold tracking-wider opacity-85">Porcentaje Global</p>
            <h3 class="text-3xl font-black tracking-tight mt-1">{{ $porcentajeGlobal }}%</h3>
        </div>
        <div class="mt-4 flex items-center justify-between text-[11px] font-bold">
            <span>Cumplimiento Escolar</span>
            <span class="px-2 py-0.5 rounded-full bg-black/5 dark:bg-white/10 uppercase">
                {{ $porcentajeGlobal >= 90 ? 'Excelente' : ($porcentajeGlobal >= 50 ? 'En Proceso' : 'Crítico') }}
            </span>
        </div>
    </div>

    {{-- KPI: Docentes Activos --}}
    <div class="card p-5 flex flex-col justify-between hover:scale-[1.01] transition-transform">
        <div>
            <p class="text-xs text-slate-500 uppercase font-extrabold tracking-wider">Docentes Evaluados</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200 mt-1">{{ $docentes->count() }}</h3>
        </div>
        <div class="mt-4 text-[11px] text-slate-400 font-bold uppercase">
            <span>Docentes con carga asignada</span>
        </div>
    </div>

    {{-- KPI: Carga Total Asignada --}}
    <div class="card p-5 flex flex-col justify-between hover:scale-[1.01] transition-transform">
        <div>
            <p class="text-xs text-slate-500 uppercase font-extrabold tracking-wider">Asignaciones Escolares</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200 mt-1">{{ $totalAsignacionesGlobal }}</h3>
        </div>
        <div class="mt-4 text-[11px] text-slate-400 font-bold uppercase flex justify-between">
            <span>Completadas: <strong class="text-emerald-500 font-extrabold">{{ $totalCompletadasGlobal }}</strong></span>
            <span>Pendientes: <strong class="text-rose-500 font-extrabold">{{ $totalAsignacionesGlobal - $totalCompletadasGlobal }}</strong></span>
        </div>
    </div>

    {{-- KPI: Archivos Totales Subidos --}}
    <div class="card p-5 flex flex-col justify-between hover:scale-[1.01] transition-transform">
        <div>
            <p class="text-xs text-slate-500 uppercase font-extrabold tracking-wider">Documentos Subidos</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200 mt-1">{{ $totalArchivosSubidos }}</h3>
        </div>
        <div class="mt-4 text-[11px] text-slate-400 font-bold uppercase">
            <span>En este Bimestre y Año</span>
        </div>
    </div>
</div>

{{-- Tabla de Reportes --}}
<div class="card">
    <div class="card-header flex justify-between items-center print:hidden">
        <span class="card-title">📋 Estado de Carga por Docente</span>
        <span class="text-xs text-slate-400 font-medium">Año {{ $anio }} · {{ $bimestres[$bimestre] ?? $bimestre }}</span>
    </div>
    <div class="card-body !p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-500/5 border-b border-slate-200/50 dark:border-slate-800/50 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Docente</th>
                        <th class="px-6 py-4 text-center">Cursos Dictados</th>
                        <th class="px-6 py-4 text-center text-emerald-600 dark:text-emerald-400">Completados</th>
                        <th class="px-6 py-4 text-center text-rose-500">Pendientes</th>
                        <th class="px-6 py-4">Progreso de Cumplimiento</th>
                        <th class="px-6 py-4 print:hidden">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/50 dark:divide-slate-800/40 text-sm">
                    @forelse($reporte as $item)
                        @php
                            $p = $item['porcentaje'];
                            $barColor = match(true) {
                                $p >= 90 => 'bg-gradient-to-r from-emerald-500 to-teal-500',
                                $p >= 50 => 'bg-gradient-to-r from-amber-500 to-yellow-500',
                                default => 'bg-gradient-to-r from-rose-500 to-red-500'
                            };
                            $badgeColor = match(true) {
                                $p >= 90 => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                $p >= 50 => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                default => 'bg-rose-500/10 text-rose-600 dark:text-rose-400'
                            };
                        @endphp
                        <tr class="hover:bg-slate-500/5 dark:hover:bg-slate-500/5 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                <div>{{ $item['docente']->nombre_completo }}</div>
                                <span class="text-xs text-slate-400 font-mono font-medium">{{ $item['docente']->username }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                {{ $item['total_asignaciones'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $item['completadas'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-rose-500">
                                {{ $item['pendientes'] }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2 overflow-hidden max-w-[200px]">
                                        <div class="{{ $barColor }} h-full transition-all duration-500" style="width: {{ $p }}%"></div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-xs font-black tracking-tight {{ $badgeColor }}">
                                        {{ $p }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 print:hidden">
                                <button type="button" 
                                        class="btn btn-ghost btn-xs text-blue-600 hover:text-blue-500 hover:bg-blue-500/10 font-bold"
                                        onclick="abrirDetalleDocente('{{ $item['docente']->nombre_completo }}', {{ json_encode($item['detalle']) }})">
                                    👁️ Ver Detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No hay docentes registrados o activos en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@else
{{-- Estado vacío: antes de generar el reporte --}}
<div class="card">
    <div class="card-body py-16 flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-700 dark:text-slate-300 mb-1">Sin resultados aún</h3>
        <p class="text-sm text-slate-400 max-w-sm">
            Selecciona el <strong>Año Lectivo</strong> y el <strong>Bimestre</strong> que deseas analizar,
            luego haz clic en <strong>⚙️ Generar Reporte</strong>.
        </p>
    </div>
</div>

@endif

{{-- MODAL DE DETALLE DE DOCENTE (JS Vanilla) --}}
<div id="modalDetalle" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-xs flex items-center justify-center p-4 print:hidden" onclick="cerrarDetalleDocente(event)">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-3xl max-h-[85vh] flex flex-col shadow-2xl animate-fade-in" onclick="event.stopPropagation()">
        
        {{-- Cabecera --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/60 dark:border-slate-800/60">
            <div>
                <h3 class="text-base font-black text-gray-800 dark:text-slate-100 uppercase tracking-wide">
                    Detalle de Cumplimiento
                </h3>
                <p id="modalDocenteNombre" class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-0.5">
                    —
                </p>
            </div>
            <button onclick="cerrarDetalleDocente(null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Cuerpo / Tabla de asignaciones --}}
        <div class="flex-1 overflow-y-auto p-6">
            <div class="overflow-x-auto border border-slate-200/60 dark:border-slate-800/60 rounded-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-500/5 border-b border-slate-200/50 dark:border-slate-800/50 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            <th class="px-5 py-3">Curso</th>
                            <th class="px-5 py-3">Grado y Sección</th>
                            <th class="px-5 py-3">Nivel</th>
                            <th class="px-5 py-3 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetalleCuerpo" class="divide-y divide-slate-200/50 dark:divide-slate-800/40 text-xs">
                        {{-- Se llena con JS --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pie --}}
        <div class="px-6 py-4 border-t border-slate-200/60 dark:border-slate-800/60 flex justify-end">
            <button onclick="cerrarDetalleDocente(null)" class="btn btn-ghost btn-sm font-bold text-slate-500 dark:text-slate-400">
                Cerrar Detalle
            </button>
        </div>
    </div>
</div>

<script>
    function abrirDetalleDocente(nombreCompleto, detalle) {
        document.getElementById('modalDocenteNombre').innerText = nombreCompleto;
        const cuerpo = document.getElementById('modalDetalleCuerpo');
        cuerpo.innerHTML = '';

        detalle.forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-500/5 dark:hover:bg-slate-500/5 transition-colors';

            const tdCurso = document.createElement('td');
            tdCurso.className = 'px-5 py-3 font-semibold text-slate-800 dark:text-slate-200';
            tdCurso.innerText = item.curso;

            const tdGrado = document.createElement('td');
            tdGrado.className = 'px-5 py-3 text-slate-600 dark:text-slate-400 font-medium';
            tdGrado.innerText = item.grado;

            const tdNivel = document.createElement('td');
            tdNivel.className = 'px-5 py-3';
            const spanNivel = document.createElement('span');
            spanNivel.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400';
            spanNivel.innerText = item.nivel;
            tdNivel.appendChild(spanNivel);

            const tdEstado = document.createElement('td');
            tdEstado.className = 'px-5 py-3 text-center';
            const spanEstado = document.createElement('span');
            if (item.cumplido) {
                spanEstado.className = 'px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
                spanEstado.innerText = '✔️ COMPLETADO (' + item.archivos_count + ')';
            } else {
                spanEstado.className = 'px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20';
                spanEstado.innerText = '❌ PENDIENTE (0)';
            }
            tdEstado.appendChild(spanEstado);

            tr.appendChild(tdCurso);
            tr.appendChild(tdGrado);
            tr.appendChild(tdNivel);
            tr.appendChild(tdEstado);

            cuerpo.appendChild(tr);
        });

        const modal = document.getElementById('modalDetalle');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // Evitar scroll del fondo
    }

    function cerrarDetalleDocente(event) {
        if (!event || event.target === document.getElementById('modalDetalle')) {
            const modal = document.getElementById('modalDetalle');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function toggleDownloadDropdown(event) {
        event.stopPropagation();
        const menu = document.getElementById('downloadDropdownMenu');
        menu.classList.toggle('hidden');
    }

    function closeDownloadDropdown() {
        const menu = document.getElementById('downloadDropdownMenu');
        if (menu) {
            menu.classList.add('hidden');
        }
    }

    // Cerrar el menú al hacer clic en cualquier parte fuera de él
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('downloadDropdownMenu');
        const btn = document.getElementById('downloadDropdownBtn');
        if (menu && !menu.classList.contains('hidden') && !menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>

<style>
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        /* Ocultar elementos de navegación y de control */
        #sidebar, header, .print\:hidden, nav, button, a {
            display: none !important;
        }
        /* Expandir el contenido para usar todo el ancho de página */
        main, .container, .flex-1, .h-full, .min-h-screen {
            margin: 0 !important;
            padding: 0 !important;
            min-height: auto !important;
            width: 100% !important;
            max-width: 100% !important;
            position: static !important;
            transform: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin-bottom: 0 !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #cbd5e1 !important;
            padding: 8px !important;
            font-size: 11px !important;
        }
        /* Quitar fondos oscuros y degradados */
        tr {
            background: transparent !important;
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

@endsection
