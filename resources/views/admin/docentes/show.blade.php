@extends('layouts.admin')

@section('title', 'Detalle del Docente — ' . $docente->nombre_completo)
@section('page-title', $docente->nombre_completo)
@section('breadcrumb', 'Admin / Docentes / Detalle')

@section('topbar-actions')
    <div class="flex gap-2">
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
        <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-primary btn-sm">Editar</a>
        <form method="POST" action="{{ route('admin.docentes.toggleActivo', $docente) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm {{ $docente->activo ? 'btn-warning' : 'btn-success' }}">
                {{ $docente->activo ? 'Desactivar' : 'Activar' }}
            </button>
        </form>
    </div>
@endsection

@section('content')

{{-- ══ TARJETA INFO DOCENTE ════════════════════════════════════════════════ --}}
<div class="card mb-5">
    <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        {{-- Avatar --}}
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600
                    flex items-center justify-center text-2xl font-black text-white flex-shrink-0 shadow">
            {{ strtoupper(substr($docente->nombre, 0, 1)) }}
        </div>
        {{-- Datos --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <h2 class="text-lg font-extrabold text-gray-800 dark:text-slate-100">
                    {{ $docente->nombre_completo }}
                </h2>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold
                             {{ $docente->activo
                                 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'
                                 : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
                    {{ $docente->activo ? '● Activo' : '● Inactivo' }}
                </span>
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-slate-400 mt-1.5">
                <span>🪪 <strong>DNI:</strong> {{ $docente->dni ?? '—' }}</span>
                <span>👤 <strong>Usuario:</strong> {{ $docente->username }}</span>
                <span>🔑 <strong>Contraseña:</strong> <span class="font-mono text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded select-all">{{ $docente->password_plain }}</span></span>
            </div>
            <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-2">
                Miembro desde {{ $docente->created_at->format('d/m/Y') }}
            </p>
        </div>
        {{-- Stats --}}
        <div class="flex gap-3 flex-shrink-0">
            <div class="text-center px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20">
                <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $docente->asignaciones->count() }}</p>
                <p class="text-[10px] text-indigo-400 dark:text-indigo-500 uppercase tracking-widest font-semibold">Asignaciones</p>
            </div>
            <div class="text-center px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $docente->archivos->count() }}</p>
                <p class="text-[10px] text-emerald-400 dark:text-emerald-500 uppercase tracking-widest font-semibold">Archivos</p>
            </div>
        </div>
    </div>
</div>

{{-- ══ CONTENIDO ══════════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 xl:grid-cols-[320px_1fr] gap-5 items-start">

    {{-- ── Asignaciones ── --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Asignaciones</span>
            <span class="text-xs text-gray-400 dark:text-slate-500">{{ $docente->asignaciones->count() }} registros</span>
        </div>
        @if($docente->asignaciones->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">
            <p class="text-3xl mb-2">📭</p>Sin asignaciones.
        </div>
        @else
        <div class="divide-y divide-gray-100 dark:divide-slate-700/40">
            @foreach($docente->asignaciones as $asig)
            <div class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-1">
                    {{ $asig->grado->nivel->nombre ?? '—' }}
                </p>
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-800 dark:text-slate-100">{{ $asig->grado->nombre ?? '—' }}</span>
                        <span class="text-gray-300 dark:text-slate-600">·</span>
                        <span class="text-xs text-gray-400 dark:text-slate-500">Sec. {{ $asig->seccion->nombre ?? '—' }}</span>
                    </div>
                    <button type="button" 
                            class="curso-filter-btn inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-400 font-semibold text-xs transition-colors flex-shrink-0"
                            data-filter-curso-id="{{ $asig->curso_id }}"
                            data-filter-grado-id="{{ $asig->grado_id }}"
                            title="Filtrar archivos de esta asignatura en la lista">
                        <span>{{ $asig->curso->nombre ?? '—' }}</span>
                        <svg class="w-3 h-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Archivos ── --}}
    <div class="card overflow-hidden">
        <div class="card-header flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="card-title">Archivos Subidos</span>
                <span class="text-xs text-gray-400 dark:text-slate-500">{{ $docente->archivos->count() }} archivos</span>
            </div>
            <button type="button" id="clearCursoFilterBtn" class="hidden text-xs text-blue-600 hover:text-blue-700 font-bold" onclick="clearCursoFilter()">
                ✕ Mostrar Todos
            </button>
        </div>

        @if($docente->archivos->isEmpty())
        <div class="py-10 text-center text-gray-400 text-sm">
            <p class="text-3xl mb-2">📭</p>Sin archivos subidos aún.
        </div>
        @else

        {{-- Tabla --}}
        <div class="overflow-x-auto max-h-[460px] overflow-y-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700/50 bg-gray-50 dark:bg-slate-800/40">
                        <th class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Archivo</th>
                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Fecha</th>
                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Bimestre</th>
                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-400 whitespace-nowrap">Tamaño</th>
                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-400 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/30">
                @php
                    $nombresB = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
                    $bimColors = [
                        1 => 'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-400',
                        2 => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
                        3 => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                        4 => 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-400',
                    ];
                @endphp
                @foreach($docente->archivos()->latest()->get() as $archivo)
                @php
                    $ext    = strtoupper(pathinfo($archivo->nombre_original, PATHINFO_EXTENSION));
                    $icon   = match(true) {
                        str_contains($archivo->tipo_archivo,'pdf')              => '📄',
                        str_contains($archivo->tipo_archivo,'word')
                            || str_contains($archivo->tipo_archivo,'document')  => '📝',
                        str_contains($archivo->tipo_archivo,'sheet')
                            || str_contains($archivo->tipo_archivo,'excel')     => '📊',
                        str_contains($archivo->tipo_archivo,'presentation')
                            || str_contains($archivo->tipo_archivo,'powerpoint')=> '📑',
                        default                                                 => '📎',
                    };
                    $bNum   = $archivo->bimestre ?? 0;
                    $bColor = $bimColors[$bNum] ?? 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-slate-400';
                    $bLabel = isset($nombresB[$bNum]) ? $nombresB[$bNum].' Bim.' : '—';
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/20 transition-colors"
                    data-row-type="file-row"
                    data-row-curso-id="{{ $archivo->curso_id }}"
                    data-row-grado-id="{{ $archivo->grado_id }}">

                    {{-- Nombre + meta --}}
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg flex-shrink-0">{{ $icon }}</span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-slate-200 truncate max-w-[240px]"
                                   title="{{ $archivo->nombre_original }}">{{ $archivo->nombre_original }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-0.5">
                                    {{ $archivo->grado?->nombre }}/{{ $archivo->seccion?->nombre }}
                                    &nbsp;·&nbsp;
                                    <a href="#" 
                                       class="curso-filter-link inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-400 font-medium text-[10px] transition-colors"
                                       data-filter-curso-id="{{ $archivo->curso_id }}"
                                       data-filter-grado-id="{{ $archivo->grado_id }}"
                                       title="Filtrar por esta asignatura">
                                        <span>{{ $archivo->curso?->nombre }}</span>
                                        <svg class="w-2.5 h-2.5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                        </svg>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Fecha --}}
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="text-xs font-semibold text-gray-700 dark:text-slate-300">
                            {{ $archivo->created_at->format('d/m/Y') }}
                        </span>
                        <br>
                        <span class="text-[10px] text-gray-400 dark:text-slate-500 font-medium">
                            {{ $archivo->created_at->format('H:i') }}
                        </span>
                    </td>

                    {{-- Bimestre + ext en una fila --}}
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $bColor }}">{{ $bLabel }}</span>
                            <span class="text-[10px] font-mono font-bold text-gray-400 dark:text-slate-500
                                         bg-gray-100 dark:bg-slate-700 px-1.5 py-0.5 rounded">{{ $ext }}</span>
                        </div>
                    </td>

                    {{-- Tamaño --}}
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="text-xs text-gray-500 dark:text-slate-400">{{ $archivo->tamanio_formateado }}</span>
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button"
                                    class="btn btn-ghost btn-xs text-slate-400 hover:text-sky-500"
                                    title="Previsualizar"
                                    onclick="openPreviewModal('{{ route('admin.archivos.stream', $archivo) }}','{{ $ext }}','{{ addslashes($archivo->nombre_original) }}','{{ route('admin.archivos.download', $archivo) }}','{{ route('admin.archivos.signed-url', $archivo) }}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <a href="{{ route('admin.archivos.download', $archivo) }}"
                               class="btn btn-ghost btn-xs text-slate-400 hover:text-emerald-500" title="Descargar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
    let activeCursoId = null;
    let activeGradoId = null;

    document.querySelectorAll('.curso-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cursoId = this.getAttribute('data-filter-curso-id');
            const gradoId = this.getAttribute('data-filter-grado-id');
            
            if (activeCursoId === cursoId && activeGradoId === gradoId) {
                clearCursoFilter();
            } else {
                applyCursoFilter(cursoId, gradoId, this);
            }
        });
    });

    document.querySelectorAll('.curso-filter-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const cursoId = this.getAttribute('data-filter-curso-id');
            const gradoId = this.getAttribute('data-filter-grado-id');
            
            const matchingBtn = document.querySelector(`.curso-filter-btn[data-filter-curso-id="${cursoId}"][data-filter-grado-id="${gradoId}"]`);
            
            if (activeCursoId === cursoId && activeGradoId === gradoId) {
                clearCursoFilter();
            } else {
                if (matchingBtn) {
                    applyCursoFilter(cursoId, gradoId, matchingBtn);
                } else {
                    applyCursoFilter(cursoId, gradoId, this);
                }
            }
        });
    });

    function applyCursoFilter(cursoId, gradoId, activeBtn) {
        activeCursoId = cursoId;
        activeGradoId = gradoId;
        
        // Reset styles for all buttons
        document.querySelectorAll('.curso-filter-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            btn.classList.add('bg-blue-50', 'hover:bg-blue-100', 'text-blue-700', 'dark:bg-blue-500/10', 'dark:hover:bg-blue-500/20', 'dark:text-blue-400');
        });
        
        // Style selected button if it's one of the main buttons
        if (activeBtn.classList.contains('curso-filter-btn')) {
            activeBtn.classList.remove('bg-blue-50', 'hover:bg-blue-100', 'text-blue-700', 'dark:bg-blue-500/10', 'dark:hover:bg-blue-500/20', 'dark:text-blue-400');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
        }
        
        // Filter table rows
        let visibleCount = 0;
        document.querySelectorAll('[data-row-type="file-row"]').forEach(row => {
            const rowCursoId = row.getAttribute('data-row-curso-id');
            const rowGradoId = row.getAttribute('data-row-grado-id');
            
            if (rowCursoId === cursoId && rowGradoId === gradoId) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show clear button
        document.getElementById('clearCursoFilterBtn').classList.remove('hidden');
        
        // Show/hide empty state
        const emptyRow = document.getElementById('emptyFilterRow');
        if (visibleCount === 0) {
            if (!emptyRow) {
                const tr = document.createElement('tr');
                tr.id = 'emptyFilterRow';
                tr.innerHTML = `
                    <td colspan="5" class="py-12 text-center text-gray-400 text-sm">
                        <p class="text-3xl mb-2">📭</p>No hay archivos subidos para esta asignatura.
                    </td>
                `;
                document.querySelector('tbody').appendChild(tr);
            } else {
                emptyRow.style.display = '';
            }
        } else {
            if (emptyRow) emptyRow.style.display = 'none';
        }
    }

    function clearCursoFilter() {
        activeCursoId = null;
        activeGradoId = null;
        
        // Reset button styles
        document.querySelectorAll('.curso-filter-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            btn.classList.add('bg-blue-50', 'hover:bg-blue-100', 'text-blue-700', 'dark:bg-blue-500/10', 'dark:hover:bg-blue-500/20', 'dark:text-blue-400');
        });
        
        // Show all rows
        document.querySelectorAll('[data-row-type="file-row"]').forEach(row => {
            row.style.display = '';
        });
        
        // Hide clear button
        document.getElementById('clearCursoFilterBtn').classList.add('hidden');
        
        // Hide empty state
        const emptyRow = document.getElementById('emptyFilterRow');
        if (emptyRow) emptyRow.style.display = 'none';
    }
</script>
@endsection
