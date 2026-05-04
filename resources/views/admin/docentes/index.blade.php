@extends('layouts.admin')

@section('title', 'Docentes')
@section('page-title', 'Gestión de Docentes')
@section('breadcrumb', 'Admin / Docentes')

@section('topbar-actions')
    <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Docente
    </a>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">👩‍🏫 Listado de Docentes</span>
        <span class="badge badge-muted">{{ $docentes->total() }} registros</span>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Asignaciones</th>
                    <th>Archivos</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($docentes as $docente)
                <tr>
                    {{-- Avatar + nombre --}}
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($docente->nombre, 0, 1)) }}
                            </div>
                            <span class="font-medium text-sm">{{ $docente->nombre_completo }}</span>
                        </div>
                    </td>
                    <td class="text-slate-400 text-sm">{{ $docente->email }}</td>

                    {{-- Estado --}}
                    <td>
                        @if($docente->activo)
                            <span class="badge badge-success">● Activo</span>
                        @else
                            <span class="badge badge-danger">● Inactivo</span>
                        @endif
                    </td>

                    <td><span class="badge badge-primary">{{ $docente->asignaciones_count }} cursos</span></td>
                    <td><span class="badge badge-muted">{{ $docente->archivos_count }} archivos</span></td>

                    {{-- Acciones --}}
                    <td>
                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                            <a href="{{ route('admin.docentes.show', $docente) }}"
                               class="btn btn-ghost btn-xs">Ver</a>
                            <a href="{{ route('admin.docentes.edit', $docente) }}"
                               class="btn btn-primary btn-xs">Editar</a>

                            {{-- Toggle activo/inactivo --}}
                            <form method="POST" action="{{ route('admin.docentes.toggleActivo', $docente) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="btn btn-xs {{ $docente->activo ? 'btn-warning' : 'btn-success' }}">
                                    {{ $docente->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <p class="text-4xl mb-3">👩‍🏫</p>
                        <p class="text-slate-500 text-sm mb-4">No hay docentes registrados aún.</p>
                        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary btn-sm">
                            Crear primer docente
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($docentes->hasPages())
        <div class="border-t border-slate-700/30">
            {{ $docentes->links() }}
        </div>
    @endif
</div>

@endsection
