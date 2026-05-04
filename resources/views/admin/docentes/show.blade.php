@extends('layouts.admin')

@section('title', 'Detalle del Docente')
@section('page-title', $docente->nombre_completo)
@section('breadcrumb', 'Admin / Docentes / Detalle')

@section('topbar-actions')
    <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-primary btn-sm">Editar</a>
    <form method="POST" action="{{ route('admin.docentes.toggleActivo', $docente) }}">
        @csrf @method('PATCH')
        <button type="submit" class="btn btn-sm {{ $docente->activo ? 'btn-warning' : 'btn-success' }}">
            {{ $docente->activo ? 'Desactivar' : 'Activar' }}
        </button>
    </form>
@endsection

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-[300px_1fr] gap-5 items-start">

    {{-- Perfil --}}
    <div class="space-y-4">
        <div class="card p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-sky-500 flex items-center justify-center text-3xl font-extrabold mx-auto mb-4">
                {{ strtoupper(substr($docente->nombre, 0, 1)) }}
            </div>
            <h2 class="font-bold text-lg">{{ $docente->nombre_completo }}</h2>
            <p class="text-slate-400 text-sm mt-1">{{ $docente->email }}</p>
            <div class="mt-3">
                @if($docente->activo)
                    <span class="badge badge-success">● Activo</span>
                @else
                    <span class="badge badge-danger">● Inactivo</span>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">📊 Estadísticas</span></div>
            <div class="p-4 space-y-2">
                <div class="flex items-center justify-between p-3 rounded-lg bg-indigo-500/5">
                    <span class="text-xs text-slate-400">Asignaciones</span>
                    <span class="badge badge-primary">{{ $docente->asignaciones->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-500/5">
                    <span class="text-xs text-slate-400">Archivos subidos</span>
                    <span class="badge badge-success">{{ $docente->archivos->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-700/20">
                    <span class="text-xs text-slate-400">Miembro desde</span>
                    <span class="text-xs text-slate-400">{{ $docente->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Asignaciones y archivos --}}
    <div class="space-y-5">

        {{-- Asignaciones --}}
        <div class="card">
            <div class="card-header"><span class="card-title">📚 Asignaciones</span></div>
            @if($docente->asignaciones->isEmpty())
                <div class="py-12 text-center text-slate-500 text-sm">
                    <p class="text-3xl mb-2">📭</p>Sin asignaciones registradas.
                </div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Nivel</th><th>Grado</th><th>Sección</th><th>Curso</th></tr></thead>
                        <tbody>
                            @foreach($docente->asignaciones as $asig)
                            <tr>
                                <td><span class="badge badge-primary">{{ $asig->grado->nivel->nombre ?? '—' }}</span></td>
                                <td class="text-sm">{{ $asig->grado->nombre ?? '—' }}</td>
                                <td class="text-sm text-slate-400">Sección {{ $asig->seccion->nombre ?? '—' }}</td>
                                <td class="text-sm font-medium">{{ $asig->curso->nombre ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Archivos --}}
        <div class="card">
            <div class="card-header"><span class="card-title">📁 Archivos Subidos</span></div>
            @if($docente->archivos->isEmpty())
                <div class="py-12 text-center text-slate-500 text-sm">
                    <p class="text-3xl mb-2">📭</p>Sin archivos subidos aún.
                </div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>Archivo</th><th>Curso</th><th>Grado/Secc.</th><th>Tamaño</th><th>Fecha</th><th>—</th></tr></thead>
                        <tbody>
                            @foreach($docente->archivos as $archivo)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        @php $icon = match(true) {
                                            str_contains($archivo->tipo_archivo,'pdf') => '📄',
                                            str_contains($archivo->tipo_archivo,'word') || str_contains($archivo->tipo_archivo,'document') => '📝',
                                            str_contains($archivo->tipo_archivo,'sheet') || str_contains($archivo->tipo_archivo,'excel') => '📊',
                                            str_contains($archivo->tipo_archivo,'presentation') || str_contains($archivo->tipo_archivo,'powerpoint') => '📑',
                                            default => '📎',
                                        }; @endphp
                                        <span class="text-lg">{{ $icon }}</span>
                                        <span class="text-xs font-medium max-w-[160px] truncate" title="{{ $archivo->nombre_original }}">{{ $archivo->nombre_original }}</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary">{{ $archivo->curso->nombre }}</span></td>
                                <td class="text-xs text-slate-400">{{ $archivo->grado->nombre }}/{{ $archivo->seccion->nombre }}</td>
                                <td class="text-xs text-slate-500">{{ $archivo->tamanio_formateado }}</td>
                                <td class="text-xs text-slate-500">{{ $archivo->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.archivos.download', $archivo) }}" class="btn btn-ghost btn-xs">⬇</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
