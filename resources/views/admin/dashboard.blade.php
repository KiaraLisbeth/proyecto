@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Inicio')

@section('content')

{{-- ══ KPI CARDS ═══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Docentes activos --}}
    <div class="card p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-3">
            Docentes Activos
        </p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $totalDocentes }}</p>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">Registrados en el sistema</p>
    </div>

    {{-- Total archivos --}}
    <div class="card p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-3">
            Total Archivos
        </p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $totalArchivos }}</p>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">En todos los bimestres</p>
    </div>

    {{-- Promedio por Docente --}}
    <div class="card p-5">
        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-3">
            Promedio / Docente
        </p>
        <div class="flex items-end justify-between">
            <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $promedioXDocente }}</p>
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">archivos por docente</p>
    </div>

</div>

{{-- ══ FILA 2: GRÁFICOS ════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Archivos por Bimestre (Barras) --}}
    <div class="card lg:col-span-2 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-extrabold text-gray-800 dark:text-slate-100">Archivos por Bimestre</p>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                    {{ array_sum($bimestreData) }} sesiones totales
                </p>
            </div>
        </div>
        <div class="relative h-48">
            <canvas id="chartBimestre"></canvas>
        </div>
    </div>

    {{-- Archivos por Nivel (Dona) --}}
    <div class="card p-5">
        <p class="text-sm font-extrabold text-gray-800 dark:text-slate-100 mb-1">Por Nivel</p>
        <p class="text-xs text-gray-400 dark:text-slate-500 mb-4">Distribución por nivel educativo</p>
        <div class="relative h-40 flex items-center justify-center">
            <canvas id="chartNivel"></canvas>
        </div>
        <div class="mt-4 space-y-1.5">
            @php $nivelColors = ['#6366f1','#3b82f6','#14b8a6']; @endphp
            @foreach($archivosPorNivel as $i => $nivel)
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                          style="background:{{ $nivelColors[$i] ?? '#9ca3af' }}"></span>
                    <span class="text-gray-600 dark:text-slate-400">{{ $nivel->nombre }}</span>
                </div>
                <span class="font-bold text-gray-700 dark:text-slate-300">{{ $nivel->grados->sum('archivos_count') }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ══ FILA 3: TABLA + RANKING ═════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 xl:grid-cols-[1fr_300px] gap-5">

    {{-- Actividad Reciente — Feed ──────────────────────────────────────────────── --}}
    <div class="card overflow-hidden">
        <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
            <span class="card-title">Actividad Reciente</span>
            <a href="{{ route('admin.archivos.index') }}"
               class="text-xs font-semibold text-blue-500 hover:text-blue-700 dark:hover:text-blue-300">Ver todos →</a>
        </div>

        @php
            $bimBorder = [
                1 => 'border-l-sky-400',
                2 => 'border-l-emerald-400',
                3 => 'border-l-amber-400',
                4 => 'border-l-rose-400',
            ];
            $bimBadge = [
                1 => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-400',
                2 => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                3 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                4 => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
            ];
            $bimLabel = [1=>'I Bim.',2=>'II Bim.',3=>'III Bim.',4=>'IV Bim.'];
            $extColors = [
                'PDF'  => 'bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                'DOCX' => 'bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                'DOC'  => 'bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                'XLSX' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                'PPTX' => 'bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
                'PPT'  => 'bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400',
            ];
        @endphp

        <div class="divide-y divide-gray-50 dark:divide-slate-700/30 max-h-[400px] overflow-y-auto">
        @forelse($ultimosArchivos as $archivo)
        @php
            $ext    = strtoupper(pathinfo($archivo->nombre_original, PATHINFO_EXTENSION));
            $bNum   = $archivo->bimestre ?? 0;
            $border = $bimBorder[$bNum] ?? 'border-l-gray-300';
            $badge  = $bimBadge[$bNum]  ?? 'bg-gray-100 text-gray-500';
            $label  = $bimLabel[$bNum]  ?? '—';
            $extCls = $extColors[$ext]  ?? 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400';
        @endphp
        <div class="flex items-start gap-4 px-5 py-4 border-l-4 {{ $border }}
                    hover:bg-gray-50 dark:hover:bg-slate-700/20 transition-colors">

            {{-- Fecha (izquierda) --}}
            <div class="flex-shrink-0 text-center w-12">
                <p class="text-sm font-black text-gray-700 dark:text-slate-300 leading-none">
                    {{ $archivo->created_at->format('d') }}
                </p>
                <p class="text-[10px] font-semibold text-gray-400 dark:text-slate-500 uppercase">
                    {{ $archivo->created_at->isoFormat('MMM') }}
                </p>
            </div>

            {{-- Separador vertical --}}
            <div class="w-px self-stretch bg-gray-100 dark:bg-slate-700/50 flex-shrink-0"></div>

            {{-- Contenido --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-xs font-bold text-gray-800 dark:text-slate-200 truncate"
                       title="{{ $archivo->nombre_original }}">
                        {{ $archivo->nombre_original }}
                    </p>
                    <span class="text-[10px] text-gray-400 dark:text-slate-500 flex-shrink-0 whitespace-nowrap">
                        {{ $archivo->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1 truncate">
                    <span class="font-semibold text-gray-700 dark:text-slate-300">
                        {{ $archivo->docente->nombre_completo ?? '—' }}
                    </span>
                    @if($archivo->grado)
                    · {{ $archivo->grado->nombre }}
                    @endif
                    @if($archivo->curso)
                    · <span class="text-blue-500 dark:text-blue-400">{{ $archivo->curso->nombre }}</span>
                    @endif
                </p>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded font-mono {{ $extCls }}">{{ $ext }}</span>
                    @if($bNum)
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $badge }}">{{ $label }}</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-gray-400 text-sm">
            <p class="text-2xl mb-2">📭</p>Sin archivos aún.
        </div>
        @endforelse
        </div>
    </div>

    {{-- Panel derecho --}}
    <div class="space-y-5">

        {{-- Top Docentes --}}
        <div class="card">
            <div class="card-header border-b border-gray-100 dark:border-slate-700/50">
                <span class="card-title">Top Docentes</span>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest">por archivos subidos</span>
            </div>
            <div class="p-4 space-y-3 max-h-[300px] overflow-y-auto">
                @php $maxFiles = $topDocentes->max('archivos_count') ?: 1; @endphp
                @forelse($topDocentes as $i => $doc)
                <div class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full text-[10px] font-black flex items-center justify-center flex-shrink-0
                                 {{ $i === 0 ? 'bg-amber-400 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400' }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-700 dark:text-slate-300 truncate">
                            {{ $doc->nombre_completo }}
                        </p>
                        <div class="mt-1 h-1.5 rounded-full bg-gray-100 dark:bg-slate-700/50 overflow-hidden">
                            <div class="h-full rounded-full bg-blue-500 transition-all duration-700"
                                 style="width:{{ ($doc->archivos_count / $maxFiles) * 100 }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-black text-gray-700 dark:text-slate-300 flex-shrink-0">
                        {{ $doc->archivos_count }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Sin datos.</p>
                @endforelse
            </div>
        </div>

        {{-- Tipo de archivo --}}
        <div class="card p-4">
            <p class="text-sm font-extrabold text-gray-800 dark:text-slate-100 mb-3">Por Tipo</p>
            @php
                $tipoColors = ['PDF'=>'bg-red-500','Word'=>'bg-blue-500','Excel'=>'bg-emerald-500','PPT'=>'bg-orange-500','Otro'=>'bg-gray-400'];
                $totalTipo  = array_sum($porTipo) ?: 1;
            @endphp
            <div class="space-y-2">
                @forelse($porTipo as $tipo => $cnt)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $tipoColors[$tipo] ?? 'bg-gray-400' }}"></span>
                    <span class="text-xs text-gray-600 dark:text-slate-400 flex-1">{{ $tipo }}</span>
                    <span class="text-xs font-bold text-gray-700 dark:text-slate-300">{{ $cnt }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-slate-500 w-8 text-right">
                        {{ round(($cnt/$totalTipo)*100) }}%
                    </span>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center">Sin datos.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- ══ SCRIPTS CHART.JS ════════════════════════════════════════════════════ --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor   = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(0,0,0,0.05)';
    const labelColor  = isDark ? '#94a3b8' : '#9ca3af';
    const tooltipBg   = isDark ? '#1e293b' : '#ffffff';
    const tooltipText = isDark ? '#e2e8f0' : '#374151';

    // ── Barras: Archivos por Bimestre ──────────────────────────────────────
    new Chart(document.getElementById('chartBimestre'), {
        type: 'bar',
        data: {
            labels: ['I Bimestre','II Bimestre','III Bimestre','IV Bimestre'],
            datasets: [{
                label: 'Archivos',
                data: @json($bimestreData),
                backgroundColor: ['#6366f1','#3b82f6','#14b8a6','#f59e0b'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    borderColor: isDark ? '#334155' : '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => `  ${ctx.parsed.y} archivo${ctx.parsed.y !== 1 ? 's' : ''}`,
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: labelColor, font: { size: 11, weight: '600' } } },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: labelColor, stepSize: 1, font: { size: 11 } }
                }
            }
        }
    });

    // ── Dona: Archivos por Nivel ───────────────────────────────────────────
    new Chart(document.getElementById('chartNivel'), {
        type: 'doughnut',
        data: {
            labels: @json($nivelLabels),
            datasets: [{
                data: @json($nivelData),
                backgroundColor: ['#6366f1','#3b82f6','#14b8a6'],
                borderWidth: 3,
                borderColor: isDark ? '#0f172a' : '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    borderColor: isDark ? '#334155' : '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                }
            }
        }
    });
});
</script>
@endsection

@endsection
