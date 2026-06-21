@extends('layouts.admin')

@section('title', 'Papelera de Reciclaje Global')
@section('page-title', 'Papelera de Reciclaje Global')
@section('breadcrumb', 'Admin / Documentos / Papelera')

@section('topbar-actions')
    <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver a Archivos
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header bg-slate-800/50">
        <span class="card-title text-slate-300">🗑️ Archivos Eliminados en el Sistema</span>
        <div class="flex items-center gap-3">
            <span class="badge badge-muted">{{ $archivos->total() }} registros</span>
        </div>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Docente</th>
                    <th>Curso</th>
                    <th>Tamaño</th>
                    <th>Eliminado el</th>
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
                                <p class="text-xs font-semibold truncate max-w-[180px] text-slate-400 line-through"
                                   title="{{ $archivo->nombre_original }}">
                                    {{ $archivo->nombre_original }}
                                </p>
                                <span class="badge badge-muted text-xs font-mono">{{ $ext }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.docentes.show', $archivo->docente) }}"
                           class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors font-medium">
                            {{ $archivo->docente->nombre_completo }}
                        </a>
                    </td>
                    <td><span class="badge badge-primary opacity-75">{{ $archivo->curso->nombre }}</span></td>
                    <td class="text-xs text-slate-500 whitespace-nowrap">{{ $archivo->tamanio_formateado }}</td>
                    <td class="text-xs text-red-400 whitespace-nowrap">
                        {{ $archivo->deleted_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('admin.archivos.restaurar', $archivo->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-xs" title="Restaurar Archivo">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Restaurar
                                </button>
                            </form>

                            <button type="button"
                                    class="btn btn-danger btn-xs"
                                    title="Eliminar Definitivamente"
                                    onclick="openModal(
                                        '{{ route('admin.archivos.forzarEliminacion', $archivo->id) }}',
                                        'Estás a punto de eliminar DEFINITIVAMENTE el archivo &quot;{{ addslashes($archivo->nombre_original) }}&quot;. Esta acción no se puede deshacer y el archivo se perderá para siempre del servidor.'
                                    )">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Destruir
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <p class="text-4xl mb-3">🍃</p>
                        <p class="text-slate-500 text-sm">La papelera global está vacía.</p>
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
