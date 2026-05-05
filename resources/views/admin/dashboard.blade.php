@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Admin / Inicio')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-7">

    <div class="stat-card border-t-2 border-t-blue-500">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Docentes Activos</p>
        <p class="text-4xl font-extrabold text-blue-600 mt-2 mb-1">{{ $totalDocentes }}</p>
        <p class="text-xs text-gray-400">Registrados en el sistema</p>
        <div class="absolute top-5 right-5 text-4xl opacity-60">👩‍🏫</div>
    </div>

    <div class="stat-card border-t-2 border-t-emerald-500">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total de Archivos</p>
        <p class="text-4xl font-extrabold text-emerald-600 mt-2 mb-1">{{ $totalArchivos }}</p>
        <p class="text-xs text-gray-400">Subidos en el sistema</p>
        <div class="absolute top-5 right-5 text-4xl opacity-60">📁</div>
    </div>

    <div class="stat-card border-t-2 border-t-amber-500">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Niveles Educativos</p>
        <p class="text-4xl font-extrabold text-amber-500 mt-2 mb-1">{{ $archivosPorNivel->count() }}</p>
        <p class="text-xs text-gray-400">Inicial, Primaria, Secundaria</p>
        <div class="absolute top-5 right-5 text-4xl opacity-60">🏫</div>
    </div>

</div>

{{-- Grid de contenido --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    {{-- Archivos por nivel y grado --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Archivos por Nivel y Grado</span>
        </div>
        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            @forelse($archivosPorNivel as $nivel)
                <div class="p-4">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                        @if($nivel->nombre === 'Inicial') 🌱
                        @elseif($nivel->nombre === 'Primaria') 📚
                        @else 🎓 @endif
                        {{ $nivel->nombre }}
                    </p>
                    <div class="space-y-1.5">
                        @forelse($nivel->grados as $grado)
                            <div class="flex items-center justify-between py-1.5 px-3 rounded-lg bg-blue-50">
                                <span class="text-xs text-gray-600">{{ $grado->nombre }}</span>
                                <span class="badge {{ $grado->archivos_count > 0 ? 'badge-primary' : 'badge-muted' }}">
                                    {{ $grado->archivos_count }} archivos
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 px-3">Sin grados registrados</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-sm">Sin datos disponibles.</div>
            @endforelse
        </div>
    </div>

    {{-- Últimos archivos --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🕐 Archivos Recientes</span>
            <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm">Ver todos →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($ultimosArchivos as $archivo)
                <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 transition-colors">
                    @php
                        $ext = pathinfo($archivo->nombre_original, PATHINFO_EXTENSION);
                        $iconClass = match(true) {
                            str_contains($archivo->tipo_archivo,'pdf') => ['📄','file-pdf'],
                            str_contains($archivo->tipo_archivo,'image') => ['🖼️','file-other'],
                            str_contains($archivo->tipo_archivo,'word') || str_contains($archivo->tipo_archivo,'document') => ['📝','file-word'],
                            str_contains($archivo->tipo_archivo,'sheet') || str_contains($archivo->tipo_archivo,'excel') => ['📊','file-excel'],
                            str_contains($archivo->tipo_archivo,'presentation') || str_contains($archivo->tipo_archivo,'powerpoint') => ['📑','file-ppt'],
                            default => ['📎','file-other'],
                        };
                    @endphp
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-lg flex-shrink-0">
                        {{ $iconClass[0] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold truncate">{{ $archivo->nombre_original }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $archivo->docente->nombre_completo }} · {{ $archivo->curso->nombre }} · {{ $archivo->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="badge badge-muted text-xs flex-shrink-0">{{ $archivo->tamanio_formateado }}</span>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="text-3xl mb-3">📭</p>
                    <p class="text-gray-400 text-sm">Aún no hay archivos subidos.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Accesos rápidos --}}
<div class="card mt-5">
    <div class="card-header">
        <span class="card-title">⚡ Accesos Rápidos</span>
    </div>
    <div class="card-body flex flex-wrap gap-3">
        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Docente
        </a>
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-ghost">Ver Docentes</a>
        <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost">Ver Documentos</a>
    </div>
</div>

@endsection
