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

{{-- Filtros --}}
<div class="card mb-5">
    <div class="card-header">
        <span class="card-title">🔍 Filtros</span>
        @if(request()->hasAny(['docente_id','curso_id','grado_id','seccion_id','nivel_id','fecha_desde','fecha_hasta']))
            <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm">✕ Limpiar</a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.archivos.index') }}">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">

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
                    <label class="form-label">Sección</label>
                    <select name="seccion_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach($secciones as $s)
                            <option value="{{ $s->id }}" {{ request('seccion_id') == $s->id ? 'selected' : '' }}>Sección {{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Curso</label>
                    <select name="curso_id" class="input py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Todos</option>
                        @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ request('curso_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
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
        <span class="card-title">📁 Archivos</span>
        <span class="badge badge-muted">{{ $archivos->total() }} resultados</span>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Docente</th>
                    <th>Curso</th>
                    <th>Grado / Secc.</th>
                    <th>Tipo</th>
                    <th>Tamaño</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
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
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-xl {{ $colorClass }}">{{ $icon }}</span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold truncate max-w-[180px]" title="{{ $archivo->nombre_original }}">
                                    {{ $archivo->nombre_original }}
                                </p>
                                @if($archivo->descripcion)
                                    <p class="text-xs text-slate-500 truncate max-w-[180px]">{{ $archivo->descripcion }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.docentes.show', $archivo->docente) }}"
                           class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors font-medium">
                            {{ $archivo->docente->nombre_completo }}
                        </a>
                    </td>
                    <td><span class="badge badge-primary">{{ $archivo->curso->nombre }}</span></td>
                    <td class="text-xs text-slate-400">
                        {{ $archivo->grado->nivel->nombre ?? '' }} — {{ $archivo->grado->nombre }}<br>
                        <span class="text-slate-500">Sección {{ $archivo->seccion->nombre }}</span>
                    </td>
                    <td>
                        <span class="badge badge-muted text-xs uppercase font-mono">{{ $ext }}</span>
                    </td>
                    <td class="text-xs text-slate-500">{{ $archivo->tamanio_formateado }}</td>
                    <td class="text-xs text-slate-500">
                        {{ $archivo->created_at->format('d/m/Y') }}<br>
                        <span class="text-slate-600">{{ $archivo->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button" class="btn btn-ghost btn-xs text-sky-500 hover:text-sky-600 dark:text-sky-400 dark:hover:text-sky-300 btn-preview"
                                    title="Previsualizar"
                                    data-url="{{ route('admin.archivos.stream', $archivo) }}"
                                    data-ext="{{ $ext }}"
                                    data-name="{{ $archivo->nombre_original }}"
                                    data-download="{{ route('admin.archivos.download', $archivo) }}"
                                    onclick="openPreviewModal(this.dataset.url, this.dataset.ext, this.dataset.name, this.dataset.download)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <a href="{{ route('admin.archivos.download', $archivo) }}"
                               class="btn btn-ghost btn-xs" title="Descargar">⬇</a>
                            <button type="button" class="btn btn-warning btn-xs"
                                    title="Mover a Papelera"
                                    onclick="openModal('{{ route('admin.archivos.destroy', $archivo) }}', '¿Mover el archivo &quot;{{ addslashes($archivo->nombre_original) }}&quot; a la papelera? Podrás restaurarlo más adelante.')">
                                🗑
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <p class="text-4xl mb-3">📭</p>
                        @if(request()->hasAny(['docente_id','curso_id','grado_id','seccion_id','nivel_id','fecha_desde','fecha_hasta']))
                            <p class="text-slate-500 text-sm mb-3">Sin resultados con los filtros aplicados.</p>
                            <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm">Limpiar filtros</a>
                        @else
                            <p class="text-slate-500 text-sm">Aún no hay archivos en el sistema.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($archivos->hasPages())
        <div class="border-t border-slate-700/30">
            {{ $archivos->links() }}
        </div>
    @endif
</div>

@endsection
