@extends('layouts.docente')

@section('title', 'Mis Documentos')
@section('page-title', 'Mis Documentos')
@section('breadcrumb', 'Docente / Mis Documentos')

@section('topbar-actions')
    <a href="{{ route('docente.archivos.create') }}" class="btn btn-success btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Subir Archivo
    </a>
@endsection

@section('content')

{{-- Filtros y buscador --}}
<div class="card mb-5">
    <div class="card-header">
        <span class="card-title">🔍 Buscar y Filtrar</span>
        @if(request()->hasAny(['curso_id','fecha_desde','fecha_hasta']))
            <a href="{{ route('docente.archivos.index') }}" class="btn btn-ghost btn-sm">✕ Limpiar</a>
        @endif
    </div>
    <div class="card-body">
        {{-- Búsqueda en tiempo real (JS vanilla, sin recargar) --}}
        <div class="mb-4">
            <label class="form-label">Búsqueda Rápida</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                </span>
                <input type="text" id="searchInput"
                       class="input pl-10"
                       placeholder="Buscar por nombre de archivo o descripción..."
                       value="">
            </div>
            <p class="text-xs text-slate-500 mt-1">Filtra la tabla sin recargar la página.</p>
        </div>

        {{-- Filtros con submit --}}
        <form method="GET" action="{{ route('docente.archivos.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="form-label">Curso</label>
                    <select name="curso_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todos mis cursos</option>
                        @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="input py-2 text-xs"
                           value="{{ request('fecha_desde') }}" onchange="this.form.submit()">
                </div>
                <div>
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="input py-2 text-xs"
                           value="{{ request('fecha_hasta') }}" onchange="this.form.submit()">
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de archivos --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📁 Mis Archivos</span>
        <div class="flex items-center gap-3">
            <span class="badge badge-muted" id="visibleCount">{{ $archivos->total() }} registros</span>
        </div>
    </div>

    <div class="table-wrap" id="tableContainer">
        <table class="table" id="archivosTable">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Curso</th>
                    <th>Grado / Sección</th>
                    <th>Descripción</th>
                    <th>Tamaño</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="archivosBody">
                @forelse($archivos as $archivo)
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
                <tr class="archivo-row"
                    data-nombre="{{ strtolower($archivo->nombre_original) }}"
                    data-descripcion="{{ strtolower($archivo->descripcion ?? '') }}">

                    {{-- Nombre e ícono --}}
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-xl {{ $colorClass }} flex-shrink-0">{{ $icon }}</span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold truncate max-w-[180px]"
                                   title="{{ $archivo->nombre_original }}">
                                    {{ $archivo->nombre_original }}
                                </p>
                                <span class="badge badge-muted text-xs font-mono">{{ $ext }}</span>
                            </div>
                        </div>
                    </td>

                    <td><span class="badge badge-primary">{{ $archivo->curso->nombre }}</span></td>

                    <td class="text-xs text-slate-400">
                        <span class="font-medium">{{ $archivo->grado->nombre }}</span>
                        / Sec. <span class="font-medium">{{ $archivo->seccion->nombre }}</span>
                        <br><span class="text-slate-600">{{ $archivo->grado->nivel->nombre ?? '' }}</span>
                    </td>

                    <td class="text-xs text-slate-400 max-w-[160px]">
                        <span class="truncate block" title="{{ $archivo->descripcion }}">
                            {{ $archivo->descripcion ?: '—' }}
                        </span>
                    </td>

                    <td class="text-xs text-slate-500 whitespace-nowrap">{{ $archivo->tamanio_formateado }}</td>

                    <td class="text-xs text-slate-500 whitespace-nowrap">
                        {{ $archivo->created_at->format('d/m/Y') }}<br>
                        <span class="text-slate-600">{{ $archivo->created_at->format('H:i') }}</span>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('docente.archivos.download', $archivo) }}"
                               class="btn btn-ghost btn-xs" title="Descargar">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            <button type="button"
                                    class="btn btn-danger btn-xs"
                                    title="Eliminar"
                                    onclick="openModal(
                                        '{{ route('docente.archivos.destroy', $archivo) }}',
                                        'Estás a punto de eliminar el archivo &quot;{{ addslashes($archivo->nombre_original) }}&quot;. Esta acción no se puede deshacer y borrará el archivo del servidor.'
                                    )">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="py-16 text-center">
                        <p class="text-4xl mb-3">📭</p>
                        @if(request()->hasAny(['curso_id','fecha_desde','fecha_hasta']))
                            <p class="text-slate-500 text-sm mb-3">Sin archivos con los filtros aplicados.</p>
                            <a href="{{ route('docente.archivos.index') }}" class="btn btn-ghost btn-sm">Limpiar</a>
                        @else
                            <p class="text-slate-500 text-sm mb-3">Aún no has subido ningún archivo.</p>
                            <a href="{{ route('docente.archivos.create') }}" class="btn btn-success btn-sm">Subir primero</a>
                        @endif
                    </td>
                </tr>
                @endforelse

                {{-- Fila cuando la búsqueda en tiempo real no encuentra nada --}}
                <tr id="noSearchResults" class="hidden">
                    <td colspan="7" class="py-12 text-center text-slate-500 text-sm">
                        <p class="text-3xl mb-2">🔍</p>
                        No se encontraron archivos que coincidan con tu búsqueda.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($archivos->hasPages())
        <div class="border-t border-slate-700/30">
            {{ $archivos->links() }}
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    /**
     * Búsqueda en tiempo real sobre la tabla de archivos.
     * Filtra por el atributo data-nombre y data-descripcion de cada fila,
     * sin hacer peticiones al servidor ni recargar la página.
     */
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('.archivo-row');
    const noResults = document.getElementById('noSearchResults');
    const visibleCount = document.getElementById('visibleCount');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibles = 0;

        rows.forEach(row => {
            const nombre = row.dataset.nombre || '';
            const descripcion = row.dataset.descripcion || '';
            const coincide = nombre.includes(query) || descripcion.includes(query);

            row.style.display = coincide ? '' : 'none';
            if (coincide) visibles++;
        });

        // Mostrar/ocultar el mensaje de "sin resultados"
        if (noResults) {
            noResults.classList.toggle('hidden', visibles > 0 || rows.length === 0);
        }

        // Actualizar el contador de resultados visibles
        if (visibleCount) {
            visibleCount.textContent = query
                ? `${visibles} de ${rows.length} archivos`
                : `{{ $archivos->total() }} registros`;
        }
    });

    // Limpiar búsqueda al presionar Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection
