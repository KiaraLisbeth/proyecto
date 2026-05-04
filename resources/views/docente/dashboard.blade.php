@extends('layouts.docente')

@section('title', 'Mi Dashboard')
@section('page-title', 'Mi Dashboard')
@section('breadcrumb', 'Docente / Inicio')

@section('content')

{{-- Bienvenida --}}
<div class="card mb-6 p-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-teal-500/5 pointer-events-none"></div>
    <div class="relative flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-2xl font-extrabold flex-shrink-0 shadow-lg shadow-emerald-500/30">
            {{ strtoupper(substr($docente->nombre, 0, 1)) }}
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Bienvenido de vuelta</p>
            <h2 class="text-xl font-bold mt-0.5">{{ $docente->nombre_completo }}</h2>
            <p class="text-sm text-slate-400 mt-1">
                Tienes <span class="text-emerald-400 font-semibold">{{ $docente->asignaciones->count() }}</span> asignaciones activas
                y <span class="text-indigo-400 font-semibold">{{ $totalArchivos }}</span> archivos subidos.
            </p>
        </div>
        <div class="ml-auto hidden sm:block">
            <a href="{{ route('docente.archivos.create') }}" class="btn btn-success">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Subir Archivo
            </a>
        </div>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="stat-card border-t-2 border-t-emerald-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mis Asignaciones</p>
        <p class="text-4xl font-extrabold text-emerald-400 mt-2 mb-1">{{ $docente->asignaciones->count() }}</p>
        <p class="text-xs text-slate-500">Cursos asignados</p>
        <div class="absolute top-5 right-5 text-3xl opacity-10">📚</div>
    </div>
    <div class="stat-card border-t-2 border-t-indigo-500">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mis Archivos</p>
        <p class="text-4xl font-extrabold text-indigo-400 mt-2 mb-1">{{ $totalArchivos }}</p>
        <p class="text-xs text-slate-500">Total subidos</p>
        <div class="absolute top-5 right-5 text-3xl opacity-10">📁</div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    {{-- Mis Asignaciones --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📚 Mis Asignaciones</span>
        </div>

        @if($docente->asignaciones->isEmpty())
            <div class="py-12 text-center text-slate-500 text-sm">
                <p class="text-3xl mb-2">📋</p>
                <p>No tienes asignaciones registradas.</p>
                <p class="text-xs text-slate-600 mt-1">Contacta al administrador.</p>
            </div>
        @else
            <div class="divide-y divide-slate-700/30">
                @foreach($docente->asignaciones as $asig)
                <div class="flex items-center justify-between px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-emerald-400 text-sm">📖</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">{{ $asig->curso->nombre }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $asig->grado->nivel->nombre }} · {{ $asig->grado->nombre }} · Sección {{ $asig->seccion->nombre }}
                            </p>
                        </div>
                    </div>
                    <span class="badge badge-primary text-xs flex-shrink-0">
                        {{ $asig->grado->nivel->nombre }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Últimos archivos --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🕐 Mis Últimos Archivos</span>
            <a href="{{ route('docente.archivos.index') }}" class="btn btn-ghost btn-sm">Ver todos →</a>
        </div>

        @if($ultimosArchivos->isEmpty())
            <div class="py-12 text-center text-slate-500 text-sm">
                <p class="text-3xl mb-2">📭</p>
                <p class="mb-3">Aún no has subido archivos.</p>
                <a href="{{ route('docente.archivos.create') }}" class="btn btn-success btn-sm">Subir primero</a>
            </div>
        @else
            <div class="divide-y divide-slate-700/30">
                @foreach($ultimosArchivos as $archivo)
                @php $icon = match(true) {
                    str_contains($archivo->tipo_archivo,'pdf') => '📄',
                    str_contains($archivo->tipo_archivo,'word') || str_contains($archivo->tipo_archivo,'document') => '📝',
                    str_contains($archivo->tipo_archivo,'sheet') || str_contains($archivo->tipo_archivo,'excel') => '📊',
                    str_contains($archivo->tipo_archivo,'presentation') || str_contains($archivo->tipo_archivo,'powerpoint') => '📑',
                    default => '📎',
                }; @endphp
                <div class="flex items-center gap-3 px-5 py-3.5">
                    <span class="text-xl flex-shrink-0">{{ $icon }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold truncate">{{ $archivo->nombre_original }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $archivo->curso->nombre }} · {{ $archivo->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="badge badge-muted flex-shrink-0">{{ $archivo->tamanio_formateado }}</span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
