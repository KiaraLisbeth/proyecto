@extends('layouts.admin')

@section('title', 'Documentos')

@section('page-title', 'Documentos del Sistema')

@section('breadcrumb', 'Admin / Documentos')

@section('topbar-actions')
    <a href="{{ route('admin.archivos.papelera') }}" class="btn btn-ghost btn-sm text-slate-400 hover:text-white">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Ver Papelera
    </a>
@endsection

@section('content')

{{-- Filtros y buscador --}}
<div class="card mb-5">
    <div class="card-header">
        <span class="card-title">🔍 Buscar y Filtrar</span>
        @if(request()->hasAny(['docente_id','curso_id','curso_nombre','grado_id','nivel_id','bimestre','anio','buscar']))
            <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm">✕ Limpiar</a>
        @endif
    </div>
    <div class="card-body">
        {{-- Filtros con submit --}}
        <form method="GET" action="{{ route('admin.archivos.index') }}">
            {{-- Botón de envío invisible para que el navegador permita enviar el formulario con la tecla Enter --}}
            <button type="submit" class="hidden"></button>
            {{-- Búsqueda en tiempo real (JS vanilla, sin recargar) o presionando Enter --}}
            <div class="mb-4">
                <label class="form-label">Búsqueda Rápida</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                    </span>
                    <input type="text" id="searchInput" name="buscar"
                           class="input !pl-10"
                           placeholder="Buscar por nombre de archivo, descripción, docente o curso..."
                           value="{{ request('buscar') }}">
                </div>
                <p class="text-xs text-slate-500 mt-1">Filtra en pantalla al escribir, o presiona Enter para buscar en todo el sistema.</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                {{-- Año Lectivo --}}
                <div>
                    <label class="form-label">Año Lectivo</label>
                    <select name="anio" class="input py-2 text-xs font-semibold" onchange="this.form.submit()">
                        @foreach($anios as $a => $etiqueta)
                            <option value="{{ $a }}" {{ $anioSeleccionado == $a ? 'selected' : '' }}>
                                📅 {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Bimestre</label>
                    <select name="bimestre" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="" {{ request('bimestre') === null ? 'selected' : '' }}>— Seleccionar —</option>
                        @foreach($bimestres as $num => $nombre)
                            <option value="{{ $num }}" {{ request('bimestre') !== null && request('bimestre') == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Docente</label>
                    <select name="docente_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($docentes as $d)
                            <option value="{{ $d->id }}" {{ request('docente_id') == $d->id ? 'selected' : '' }}>{{ $d->nombre_completo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Nivel</label>
                    <select name="nivel_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($niveles as $n)
                            <option value="{{ $n->id }}" {{ request('nivel_id') == $n->id ? 'selected' : '' }}>{{ $n->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Grado</label>
                    <select name="grado_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($grados as $g)
                            <option value="{{ $g->id }}" {{ request('grado_id') == $g->id ? 'selected' : '' }}>{{ $g->nivel->nombre }} — {{ $g->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Curso</label>
                    <input type="text" name="curso_nombre" list="cursos-list" class="input py-2 text-xs" 
                           placeholder="Todos" 
                           value="{{ request('curso_nombre') ?: ($cursos->firstWhere('id', request('curso_id'))?->nombre ?? '') }}" 
                           onchange="this.form.submit()">
                    <datalist id="cursos-list">
                        @foreach($cursos as $c)
                            <option value="{{ $c->nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Archivos agrupados por Bimestre --}}
@php
    $archivosAgrupados = $archivos->getCollection()->groupBy('bimestre')->sortKeys();
    $nombresB = [1 => 'I Bimestre', 2 => 'II Bimestre', 3 => 'III Bimestre', 4 => 'IV Bimestre'];
    $coloresB  = [1 => 'from-sky-500 to-blue-600', 2 => 'from-emerald-500 to-teal-600',
                  3 => 'from-violet-500 to-purple-600', 4 => 'from-rose-500 to-pink-600'];
@endphp

@if($archivos->isEmpty())
<div class="card">
    <div class="py-16 text-center">
        <p class="text-4xl mb-3">📭</p>
        @if(request()->hasAny(['docente_id','curso_id','curso_nombre','grado_id','nivel_id','bimestre','fecha_desde','fecha_hasta']))
            <p class="text-slate-500 text-sm mb-1">No se encontraron archivos con los filtros seleccionados.</p>
            <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm mt-3">Ver todos</a>
        @else
            <p class="text-slate-500 text-sm mb-1">Seleccione un docente o bimestre para listar los documentos.</p>
        @endif
    </div>
</div>
@else

@php
    $bimestreFiltro = request('bimestre');
    $bimestresAMostrar = $bimestreFiltro ? [(int)$bimestreFiltro] : [1, 2, 3, 4];
    $gridColsClass = match(count($bimestresAMostrar)) {
        1 => 'grid-cols-1',
        default => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-4',
    };
@endphp

<div class="grid {{ $gridColsClass }} gap-5 items-start">
    @foreach($bimestresAMostrar as $i)
        @php
            $archivosGrupo = $archivosAgrupados->get($i) ?? collect();
        @endphp
        <div class="flex flex-col h-full bg-slate-500/5 dark:bg-slate-900/20 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-5" id="bimestre-{{ $i }}">
            {{-- Encabezado del Bimestre --}}
            <div class="flex items-center gap-3 mb-5 border-b border-slate-200/60 dark:border-slate-800/60 pb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br {{ $coloresB[$i] }} text-white font-black text-sm shadow">
                    {{ ['I','II','III','IV'][$i - 1] }}
                </div>
                <div>
                    <h3 class="font-black text-gray-800 dark:text-slate-100 text-sm tracking-wide uppercase">
                        {{ $nombresB[$i] }}
                    </h3>
                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">
                        {{ $archivosGrupo->count() }} {{ $archivosGrupo->count() == 1 ? 'sesión' : 'sesiones' }}
                    </p>
                </div>
            </div>

            {{-- Lista del Bimestre --}}
            <div class="flex-1 space-y-4 overflow-y-auto max-h-[60vh] pr-1">
                @forelse($archivosGrupo as $archivo)
                    @php
                        $ext = strtoupper(pathinfo($archivo->nombre_original, PATHINFO_EXTENSION));
                        [$icon, $colorClass] = match(true) {
                            str_contains($archivo->tipo_archivo,'pdf') => ['📄','file-pdf'],
                            str_contains($archivo->tipo_archivo,'word') || str_contains($archivo->tipo_archivo,'document') => ['📝','file-word'],
                            str_contains($archivo->tipo_archivo,'sheet') || str_contains($archivo->tipo_archivo,'excel') => ['📊','file-excel'],
                            str_contains($archivo->tipo_archivo,'presentation') || str_contains($archivo->tipo_archivo,'powerpoint') => ['📑','file-ppt'],
                            default => ['📎','file-other'],
                        };
                    @endphp
                    <div class="p-4 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800/80 rounded-xl hover:shadow-md transition-all duration-300 relative group"
                         data-card-type="file-card"
                         data-card-title="{{ $archivo->nombre_original }}"
                         data-card-desc="{{ $archivo->descripcion ?? '' }}"
                         data-card-docente="{{ $archivo->docente->nombre_completo ?? '' }}"
                         data-card-curso="{{ $archivo->curso->nombre }}">
                        <!-- Icon + Title -->
                        <div class="flex items-start gap-3 mb-3">
                            <span class="text-2xl flex-shrink-0 mt-0.5">{{ $icon }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800 dark:text-slate-200 truncate pr-4" title="{{ $archivo->nombre_original }}">
                                    {{ $archivo->nombre_original }}
                                </p>
                                <span class="text-xs text-slate-400 font-mono">{{ $ext }} · {{ $archivo->tamanio_formateado }}</span>
                            </div>
                        </div>

                        <!-- Badges -->
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            <span class="px-2 py-0.5 rounded text-[10px] sm:text-xs font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">{{ $archivo->curso->nombre }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] sm:text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $archivo->grado->nombre }} Sec. {{ $archivo->seccion->nombre }}</span>
                            @if($archivo->grado->nivel)
                                <span class="px-2 py-0.5 rounded text-[10px] sm:text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">{{ $archivo->grado->nivel->nombre }}</span>
                            @endif
                        </div>

                        <!-- Docente Info -->
                        <div class="flex items-center gap-1.5 mb-3 px-2.5 py-1.5 bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/15 rounded text-xs text-amber-700 dark:text-amber-400">
                            <span class="font-bold">Docente:</span>
                            <span class="truncate" title="{{ $archivo->docente->nombre_completo ?? '—' }}">
                                {{ $archivo->docente->nombre_completo ?? '—' }}
                            </span>
                        </div>

                        <!-- Descripcion -->
                        @if($archivo->descripcion)
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-3 line-clamp-2 leading-relaxed bg-slate-50 dark:bg-slate-950/50 p-2 rounded" title="{{ $archivo->descripcion }}">
                                {{ $archivo->descripcion }}
                            </p>
                        @endif

                        <!-- Footer: Fecha y Acciones -->
                        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800/40 pt-3 mt-3">
                            <span class="text-xs text-slate-400" title="{{ $archivo->created_at->format('d/m/Y H:i') }}">{{ $archivo->created_at->format('d/m/Y') }}</span>
                            
                            <div class="flex items-center gap-1">
                                <!-- Preview -->
                                <button type="button" class="p-1 text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 transition-colors"
                                        title="Previsualizar"
                                        data-url="{{ route('admin.archivos.stream', $archivo) }}"
                                        data-signed-url="{{ route('admin.archivos.signed-url', $archivo) }}"
                                        data-ext="{{ $ext }}"
                                        data-name="{{ $archivo->nombre_original }}"
                                        data-download="{{ route('admin.archivos.download', $archivo) }}"
                                        onclick="openPreviewModal(this.dataset.url, this.dataset.ext, this.dataset.name, this.dataset.download, this.dataset.signedUrl)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <!-- Download -->
                                <a href="{{ route('admin.archivos.download', $archivo) }}"
                                   class="p-1 text-slate-400 hover:text-emerald-500 transition-colors" title="Descargar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <!-- Trash -->
                                <button type="button" class="p-1 text-slate-400 hover:text-rose-500 transition-colors"
                                        title="Mover a Papelera"
                                        onclick="openModal('{{ route('admin.archivos.destroy', $archivo) }}', '¿Deseas mover el archivo &quot;{{ addslashes($archivo->nombre_original) }}&quot; a la papelera?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center border border-dashed border-slate-200/60 dark:border-slate-800/40 rounded-xl">
                        <p class="text-2xl opacity-40 mb-1">📭</p>
                        <p class="text-xs text-slate-400">Sin archivos en este bimestre</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

{{-- Paginación --}}
@if($archivos->hasPages())
    <div class="card mt-5">
        {{ $archivos->links() }}
    </div>
@endif

@endif

@section('scripts')
<script>
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('[data-card-type="file-card"]');
        cards.forEach(card => {
            const title = card.getAttribute('data-card-title').toLowerCase();
            const desc = card.getAttribute('data-card-desc').toLowerCase();
            const docente = card.getAttribute('data-card-docente').toLowerCase();
            const curso = card.getAttribute('data-card-curso').toLowerCase();
            
            if (title.includes(term) || desc.includes(term) || docente.includes(term) || curso.includes(term)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection

@endsection
