@extends('layouts.admin')

@section('title', 'Años Lectivos')
@section('page-title', 'Años Lectivos')
@section('breadcrumb', 'Admin / Años Lectivos')

@section('content')
@php
    $actual   = $anios->firstWhere('activo', true);
    $nombresB = [1=>'I Bimestre', 2=>'II Bimestre', 3=>'III Bimestre', 4=>'IV Bimestre'];
    $coloresB = [
        1 => ['grad'=>'from-sky-500 to-blue-600',         'badge'=>'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',      'border'=>'border-sky-400',    'text'=>'text-sky-700 dark:text-sky-300'],
        2 => ['grad'=>'from-emerald-500 to-teal-600',     'badge'=>'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400', 'border'=>'border-emerald-400','text'=>'text-emerald-700 dark:text-emerald-300'],
        3 => ['grad'=>'from-violet-500 to-purple-600',    'badge'=>'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-400',  'border'=>'border-violet-400', 'text'=>'text-violet-700 dark:text-violet-300'],
        4 => ['grad'=>'from-rose-500 to-pink-600',        'badge'=>'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',     'border'=>'border-rose-400',   'text'=>'text-rose-700 dark:text-rose-300'],
    ];
@endphp

{{-- ══ HERO ════════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-2xl mb-8 bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 border border-blue-800/40 shadow-xl">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-blue-600/10 blur-3xl"></div>
        <span class="absolute right-8 top-1/2 -translate-y-1/2 text-[9rem] font-black text-white/[0.04] select-none leading-none">
            {{ $actual?->anio ?? now()->year }}
        </span>
    </div>
    <div class="relative px-8 py-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-widest mb-1">Año Lectivo Activo</p>
            <h1 class="text-4xl font-black text-white">{{ $actual?->anio ?? '—' }}</h1>
            <p class="text-slate-400 text-sm mt-2 max-w-md">
                Haz clic en el nombre de un docente para explorar sus documentos por bimestre.
            </p>
        </div>
        <button type="button" onclick="openModalNuevoAnio()"
                class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-500
                       text-white text-sm font-bold shadow-lg transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            + Nuevo Año Lectivo
        </button>
    </div>
</div>

{{-- ══ AÑOS ════════════════════════════════════════════════════════════════ --}}
@if($anios->isEmpty())
<div class="card py-16 text-center"><p class="text-4xl mb-3">📅</p><p class="text-gray-500 text-sm">No hay años registrados.</p></div>
@else
<div class="space-y-5">
@foreach($anios as $anio)
@php $esActivo = $anio->activo; $yid = 'y'.$anio->anio; @endphp

<div class="rounded-2xl overflow-hidden border
            {{ $esActivo
                ? 'border-blue-400/50 dark:border-blue-500/40 shadow-lg shadow-blue-500/10'
                : 'border-gray-200 dark:border-slate-700/50' }}
            bg-white dark:bg-slate-800/50">

    {{-- ── Cabecera del año ── --}}
    <div class="flex items-center gap-4 px-5 py-4
                {{ $esActivo ? 'bg-blue-50 dark:bg-blue-950/30' : 'bg-gray-50 dark:bg-slate-800/30' }}">
        <div class="flex-shrink-0 text-center w-16">
            <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center font-black text-xl
                        {{ $esActivo ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-md' : 'bg-gray-200 dark:bg-slate-700/60 text-gray-500 dark:text-slate-400' }}">
                {{ substr($anio->anio, -2) }}
            </div>
            <span class="mt-1 inline-block text-[10px] font-bold px-1.5 py-0.5 rounded-full
                         {{ $esActivo
                             ? 'bg-blue-600 text-white'
                             : 'bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-slate-400' }}">
                {{ $esActivo ? 'ACTUAL' : 'ARCH.' }}
            </span>
        </div>

        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-extrabold {{ $esActivo ? 'text-blue-700 dark:text-white' : 'text-gray-500 dark:text-slate-400' }}">
                {{ $anio->anio }}
            </h2>
            <div class="flex flex-wrap gap-3 text-xs text-gray-500 dark:text-slate-500 mt-0.5">
                @if($anio->fecha_inicio) <span>📅 {{ $anio->fecha_inicio->format('d/m/Y') }}</span> @endif
                <span>📄 {{ $anio->total_archivos }} documentos</span>
                <span>👨‍🏫 {{ $anio->docentes_data->count() }} docentes</span>
            </div>
        </div>

        <div class="flex-shrink-0 flex items-center gap-2">
            @if(!$esActivo)
            <form method="POST" action="{{ route('admin.anios.activar', $anio) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold border
                               border-emerald-500 text-emerald-700 dark:text-emerald-400
                               hover:bg-emerald-50 dark:hover:bg-emerald-600/10 transition-colors">
                    ✓ Activar
                </button>
            </form>
            @endif

            @if($anio->docentes_data->isNotEmpty())
            <button type="button" onclick="togglePanel('{{ $yid }}')" id="btn-{{ $yid }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all
                           {{ $esActivo
                               ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm'
                               : 'bg-gray-700 hover:bg-gray-600 dark:bg-slate-700 dark:hover:bg-slate-600 text-white' }}">
                👥 Ver docentes
                <svg id="ico-{{ $yid }}" class="w-3.5 h-3.5 transition-transform duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            @else
                <span class="text-xs text-gray-400 dark:text-slate-600 px-3">Sin documentos</span>
            @endif
        </div>
    </div>

    {{-- ── Panel docentes ── --}}
    <div id="{{ $yid }}" class="hidden border-t border-gray-200 dark:border-slate-700/30">

        <div class="px-4 py-4 bg-gray-50 dark:bg-slate-800/20">
            <p class="text-xs font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest mb-3">Docentes con documentos</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($anio->docentes_data as $userId => $data)
                @php
                    $doc = $data['docente'];
                    $did = $yid.'d'.$userId;
                @endphp
                <button type="button" onclick="toggleDocente('{{ $did }}')" id="btn-{{ $did }}"
                        class="group flex items-center gap-3 px-4 py-3 rounded-xl border text-left w-full transition-all duration-150
                               border-gray-200 dark:border-slate-700/50
                               bg-white dark:bg-slate-800/40
                               hover:border-blue-400 dark:hover:border-slate-600
                               hover:bg-blue-50 dark:hover:bg-slate-700/60">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($doc?->nombre ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 truncate">{{ $doc?->nombre_completo ?? 'Docente' }}</p>
                        <p class="text-xs text-gray-500 dark:text-slate-500">{{ $data['total'] }} {{ $data['total']==1?'documento':'documentos' }}</p>
                    </div>
                    <svg id="ico-{{ $did }}" class="w-4 h-4 text-gray-400 dark:text-slate-500 group-hover:text-blue-500 transition-all duration-200 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Paneles de bimestres por docente --}}
        @foreach($anio->docentes_data as $userId => $data)
        @php $did = $yid.'d'.$userId; $doc = $data['docente']; @endphp
        <div id="{{ $did }}" class="hidden border-t border-gray-200 dark:border-slate-700/30 bg-white dark:bg-slate-900/20">

            {{-- Header docente --}}
            <div class="flex items-center gap-3 px-6 py-3 border-b border-gray-200 dark:border-slate-700/30 bg-gray-50 dark:bg-slate-800/40">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-500 to-blue-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($doc?->nombre ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800 dark:text-slate-100">{{ $doc?->nombre_completo }}</p>
                    <p class="text-xs text-gray-500 dark:text-slate-500">Selecciona un bimestre para ver sus documentos</p>
                </div>
                <button type="button" onclick="toggleDocente('{{ $did }}')"
                        class="ml-auto text-gray-400 hover:text-gray-700 dark:text-slate-500 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Tabs bimestres --}}
            <div class="px-6 py-4">
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach([1,2,3,4] as $bNum)
                    @php
                        $bdocs = $data['bimestres']->get($bNum, collect());
                        $bid   = $did.'b'.$bNum;
                        $cb    = $coloresB[$bNum];
                    @endphp
                    @if($bdocs->isNotEmpty())
                    <button type="button"
                            onclick="selectBimestre('{{ $did }}', '{{ $bid }}')"
                            id="tab-{{ $bid }}"
                            data-docente="{{ $did }}"
                            class="bim-tab inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border-2 transition-all duration-150
                                   border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400
                                   hover:border-gray-400 dark:hover:border-slate-500
                                   hover:text-gray-800 dark:hover:text-slate-200">
                        <span class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $cb['grad'] }} text-white text-[10px] font-extrabold flex items-center justify-center shadow-sm">
                            {{ ['I','II','III','IV'][$bNum-1] }}
                        </span>
                        {{ $nombresB[$bNum] }}
                        <span class="{{ $cb['badge'] }} px-1.5 py-0.5 rounded-md text-[10px] font-bold">{{ $bdocs->count() }}</span>
                    </button>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold border-2
                                 border-gray-100 dark:border-slate-800 text-gray-300 dark:text-slate-700 cursor-not-allowed opacity-60">
                        <span class="w-6 h-6 rounded-lg bg-gray-200 dark:bg-slate-700 text-gray-400 dark:text-slate-600 text-[10px] font-extrabold flex items-center justify-center">
                            {{ ['I','II','III','IV'][$bNum-1] }}
                        </span>
                        {{ $nombresB[$bNum] }}
                        <span class="bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-600 px-1.5 py-0.5 rounded-md text-[10px] font-bold">0</span>
                    </span>
                    @endif
                    @endforeach
                </div>

                {{-- Paneles de documentos --}}
                @foreach([1,2,3,4] as $bNum)
                @php
                    $bdocs = $data['bimestres']->get($bNum, collect());
                    $bid   = $did.'b'.$bNum;
                    $cb    = $coloresB[$bNum];
                @endphp
                @if($bdocs->isNotEmpty())
                <div id="{{ $bid }}" class="bim-panel hidden" data-docente="{{ $did }}">

                    {{-- ── Filtros tipo select ── --}}
                    @php
                        $niveles = $bdocs->map(fn($a) => $a->grado?->nivel)->filter()->unique('id')->sortBy('nombre');
                        $grados  = $bdocs->map(fn($a) => $a->grado)->filter()->unique('id')->sortBy('nombre');
                        $cursos  = $bdocs->map(fn($a) => $a->curso)->filter()->unique('id')->sortBy('nombre');
                    @endphp
                    <div class="flex flex-wrap gap-4 mb-4 p-4 rounded-xl bg-gray-50 dark:bg-slate-800/40 border border-gray-200 dark:border-slate-700/30">

                        {{-- NIVEL --}}
                        <div class="flex flex-col gap-1 min-w-[160px]">
                            <label class="text-[10px] font-extrabold tracking-widest text-teal-600 dark:text-teal-400 uppercase">Nivel</label>
                            <div class="relative">
                                <select onchange="filtrarDocsCombinado('{{ $bid }}')"
                                        id="sel-nivel-{{ $bid }}"
                                        class="w-full appearance-none rounded-lg border-2 border-gray-200 dark:border-slate-600
                                               bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                               text-sm font-semibold px-3 py-2 pr-8 cursor-pointer
                                               focus:outline-none focus:border-teal-500 transition-colors">
                                    <option value="">Todos</option>
                                    @foreach($niveles as $niv)
                                    <option value="{{ $niv->id }}">{{ $niv->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- GRADO --}}
                        <div class="flex flex-col gap-1 min-w-[160px]">
                            <label class="text-[10px] font-extrabold tracking-widest text-amber-600 dark:text-amber-400 uppercase">Grado</label>
                            <div class="relative">
                                <select onchange="filtrarDocsCombinado('{{ $bid }}')"
                                        id="sel-grado-{{ $bid }}"
                                        class="w-full appearance-none rounded-lg border-2 border-gray-200 dark:border-slate-600
                                               bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                               text-sm font-semibold px-3 py-2 pr-8 cursor-pointer
                                               focus:outline-none focus:border-amber-500 transition-colors">
                                    <option value="">Todos</option>
                                    @foreach($grados as $grd)
                                    <option value="{{ $grd->id }}">{{ $grd->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- CURSO --}}
                        <div class="flex flex-col gap-1 min-w-[160px]">
                            <label class="text-[10px] font-extrabold tracking-widest text-blue-600 dark:text-blue-400 uppercase">Curso</label>
                            <div class="relative">
                                <select onchange="filtrarDocsCombinado('{{ $bid }}')"
                                        id="sel-curso-{{ $bid }}"
                                        class="w-full appearance-none rounded-lg border-2 border-gray-200 dark:border-slate-600
                                               bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-200
                                               text-sm font-semibold px-3 py-2 pr-8 cursor-pointer
                                               focus:outline-none focus:border-blue-500 transition-colors">
                                    <option value="">Todos</option>
                                    @foreach($cursos as $cur)
                                    <option value="{{ $cur->id }}">{{ $cur->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Contador resultados --}}
                        <div class="flex items-end pb-0.5">
                            <span id="count-{{ $bid }}" class="text-xs text-gray-400 dark:text-slate-500 font-medium">
                                {{ $bdocs->count() }} archivos
                            </span>
                        </div>
                    </div>

                    {{-- ── Lista de documentos ── --}}
                    <div class="space-y-1.5" id="docs-{{ $bid }}">
                        @foreach($bdocs as $archivo)
                        @php
                            $ext  = strtoupper(pathinfo($archivo->nombre_original, PATHINFO_EXTENSION));
                            $icon = match(true) {
                                str_contains($archivo->tipo_archivo,'pdf')             => '📄',
                                str_contains($archivo->tipo_archivo,'word')
                                    || str_contains($archivo->tipo_archivo,'document') => '📝',
                                str_contains($archivo->tipo_archivo,'sheet')
                                    || str_contains($archivo->tipo_archivo,'excel')    => '📊',
                                str_contains($archivo->tipo_archivo,'presentation')
                                    || str_contains($archivo->tipo_archivo,'powerpoint')=> '📑',
                                default                                                => '📎',
                            };
                        @endphp
                        <div class="doc-item flex items-center gap-3 px-3 py-2.5 rounded-xl
                                    bg-gray-50 hover:bg-gray-100 dark:bg-slate-800/50 dark:hover:bg-slate-700/50
                                    border border-gray-100 dark:border-slate-700/30 group transition-colors"
                             data-nivel="{{ $archivo->grado?->nivel_id }}"
                             data-grado="{{ $archivo->grado_id }}"
                             data-curso="{{ $archivo->curso_id }}"
                             data-panel="{{ $bid }}">
                            <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-slate-200 truncate"
                                   title="{{ $archivo->nombre_original }}">{{ $archivo->nombre_original }}</p>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @if($archivo->grado?->nivel)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400">
                                        {{ $archivo->grado->nivel->nombre }}
                                    </span>
                                    @endif
                                    @if($archivo->grado)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400">
                                        {{ $archivo->grado->nombre }}
                                    </span>
                                    @endif
                                    @if($archivo->curso)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                                        {{ $archivo->curso->nombre }}
                                    </span>
                                    @endif
                                    <span class="text-[10px] text-gray-400 dark:text-slate-600">{{ $archivo->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-[10px] text-gray-400 dark:text-slate-600">{{ $archivo->tamanio_formateado }}</span>
                                <span class="badge badge-muted text-[10px] font-mono">{{ $ext }}</span>
                                <a href="{{ route('admin.archivos.download', $archivo) }}"
                                   class="opacity-0 group-hover:opacity-100 btn btn-ghost btn-xs transition-opacity" title="Descargar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
</div>
@endif

{{-- ══ MODAL NUEVO AÑO ══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalNuevoAnio" style="display:none!important">
    <div class="modal-box !max-w-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-100">Nuevo Año Lectivo</h3>
                <p class="text-xs text-slate-500">El año actual quedará archivado.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.anios.store') }}">
            @csrf
            <div class="space-y-4 mb-5">
                <div>
                    <label class="form-label">Año *</label>
                    <input type="number" name="anio" id="inputAnio"
                           class="input @error('anio') border-red-500 @enderror"
                           placeholder="Ej: 2027" value="{{ old('anio', now()->year + 1) }}"
                           min="2026" max="2100" required>
                    @error('anio')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio"
                           class="input @error('fecha_inicio') border-red-500 @enderror"
                           value="{{ old('fecha_inicio') }}" required>
                    @error('fecha_inicio')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs mb-5 flex gap-2 items-start">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>El año <strong>{{ $actual?->anio ?? 'actual' }}</strong> quedará como <strong>ARCHIVADO</strong>. Sus documentos se conservan.</span>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModalNuevoAnio()" class="btn btn-ghost btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Crear Año Lectivo</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function togglePanel(uid) {
    const panel = document.getElementById(uid);
    const ico   = document.getElementById('ico-' + uid);
    const open  = !panel.classList.contains('hidden');
    panel.classList.toggle('hidden', open);
    ico.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
}

function toggleDocente(did) {
    const panel = document.getElementById(did);
    const ico   = document.getElementById('ico-' + did);
    const open  = !panel.classList.contains('hidden');
    panel.classList.toggle('hidden', open);
    if (ico) ico.style.transform = open ? 'rotate(0deg)' : 'rotate(90deg)';
}

function selectBimestre(did, bid) {
    document.querySelectorAll(`.bim-panel[data-docente="${did}"]`).forEach(p => p.classList.add('hidden'));
    document.querySelectorAll(`.bim-tab[data-docente="${did}"]`).forEach(t => {
        t.classList.remove('border-blue-500','border-sky-400','border-emerald-400','border-violet-400','border-rose-400','bg-blue-50','dark:bg-blue-900/20');
        t.classList.add('border-gray-200','dark:border-slate-700','text-gray-600','dark:text-slate-400');
    });
    const panel = document.getElementById(bid);
    if (panel) panel.classList.remove('hidden');
    const tab = document.getElementById('tab-' + bid);
    if (tab) {
        tab.classList.remove('border-gray-200','dark:border-slate-700','text-gray-600','dark:text-slate-400');
        tab.classList.add('border-blue-500','text-blue-700','dark:text-white','bg-blue-50','dark:bg-blue-900/20');
    }
}

function filtrarDocsCombinado(bid) {
    const nivelVal = document.getElementById('sel-nivel-' + bid)?.value || '';
    const gradoVal = document.getElementById('sel-grado-' + bid)?.value || '';
    const cursoVal = document.getElementById('sel-curso-' + bid)?.value || '';

    let visible = 0;
    document.querySelectorAll(`.doc-item[data-panel="${bid}"]`).forEach(item => {
        const matchNivel = !nivelVal || item.getAttribute('data-nivel') == nivelVal;
        const matchGrado = !gradoVal || item.getAttribute('data-grado') == gradoVal;
        const matchCurso = !cursoVal || item.getAttribute('data-curso') == cursoVal;
        const show = matchNivel && matchGrado && matchCurso;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const counter = document.getElementById('count-' + bid);
    if (counter) counter.textContent = visible + (visible === 1 ? ' archivo' : ' archivos');
}

function openModalNuevoAnio() {
    document.getElementById('modalNuevoAnio').style.setProperty('display','flex','important');
    setTimeout(() => document.getElementById('inputAnio').focus(), 100);
}
function closeModalNuevoAnio() {
    document.getElementById('modalNuevoAnio').style.setProperty('display','none','important');
}
document.getElementById('modalNuevoAnio').addEventListener('click', function(e) {
    if (e.target === this) closeModalNuevoAnio();
});

@if($errors->any()) openModalNuevoAnio(); @endif

@if($actual && $actual->docentes_data->isNotEmpty())
    togglePanel('y{{ $actual->anio }}');
@endif
</script>
@endsection
