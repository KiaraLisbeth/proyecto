@extends('layouts.admin')

@section('title', 'Secciones')
@section('page-title', 'Gestión de Secciones')
@section('breadcrumb', 'Admin / Secciones')

@section('topbar-actions')
    <button type="button" onclick="openCreateModal()"
            class="btn btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Sección
    </button>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Lista de Secciones --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <span class="card-title">🏷️ Secciones Registradas</span>
                <span class="badge badge-muted text-xs">{{ $secciones->count() }} en total</span>
            </div>

            @if($secciones->isEmpty())
                <div class="py-16 text-center">
                    <p class="text-4xl mb-3">📂</p>
                    <p class="text-slate-500 text-sm mb-4">No hay secciones registradas aún.</p>
                    <button type="button" onclick="openCreateModal()" class="btn btn-primary btn-sm">
                        Crear primera sección
                    </button>
                </div>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sección</th>
                                <th class="text-center">Asignaciones</th>
                                <th class="text-center">Archivos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($secciones as $seccion)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600
                                                    flex items-center justify-center text-white font-extrabold text-sm shadow-sm flex-shrink-0">
                                            {{ $seccion->nombre }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800 dark:text-slate-100">
                                                Sección {{ $seccion->nombre }}
                                            </p>
                                            <p class="text-xs text-slate-400">ID #{{ $seccion->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $seccion->asignaciones_count > 0 ? 'badge-primary' : 'badge-muted' }}">
                                        {{ $seccion->asignaciones_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $seccion->archivos_count > 0 ? 'badge-success' : 'badge-muted' }}">
                                        {{ $seccion->archivos_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Editar --}}
                                        <button type="button"
                                                onclick="openEditModal({{ $seccion->id }}, '{{ $seccion->nombre }}')"
                                                class="btn btn-ghost btn-xs text-indigo-500 hover:text-indigo-600"
                                                title="Editar">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Eliminar --}}
                                        @if($seccion->archivos_count == 0 && $seccion->asignaciones_count == 0)
                                            <button type="button"
                                                    onclick="openModal('{{ route('admin.secciones.destroy', $seccion) }}', '¿Eliminar la Sección &quot;{{ $seccion->nombre }}&quot;? Esta acción no se puede deshacer.')"
                                                    class="btn btn-warning btn-xs"
                                                    title="Eliminar">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @else
                                            <span class="btn btn-ghost btn-xs text-slate-600 cursor-not-allowed opacity-40"
                                                  title="No se puede eliminar: tiene datos asociados">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                        @endif
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

    {{-- Panel lateral: info + acceso rápido --}}
    <div class="space-y-4">
        {{-- Tarjeta resumen --}}
        <div class="card">
            <div class="card-header"><span class="card-title">📊 Resumen</span></div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg bg-indigo-500/5">
                    <span class="text-xs text-slate-400">Total Secciones</span>
                    <span class="badge badge-primary">{{ $secciones->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-500/5">
                    <span class="text-xs text-slate-400">Con asignaciones</span>
                    <span class="badge badge-success">{{ $secciones->where('asignaciones_count', '>', 0)->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-amber-500/5">
                    <span class="text-xs text-slate-400">Con archivos</span>
                    <span class="badge" style="background: rgba(245,158,11,0.15); color: #d97706;">
                        {{ $secciones->where('archivos_count', '>', 0)->count() }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-500/5">
                    <span class="text-xs text-slate-400">Sin datos</span>
                    <span class="badge badge-muted">
                        {{ $secciones->where('asignaciones_count', 0)->where('archivos_count', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="card">
            <div class="card-header"><span class="card-title">ℹ️ Información</span></div>
            <div class="p-4 space-y-3 text-xs text-slate-400 leading-relaxed">
                <p>Las <strong class="text-slate-300">secciones</strong> representan los grupos de estudiantes dentro de un grado (Ej: A, B, C).</p>
                <p>Una sección <strong class="text-slate-300">no puede eliminarse</strong> si tiene asignaciones de docentes o archivos vinculados.</p>
                <p>Los nombres se guardan en <strong class="text-slate-300">mayúsculas</strong> automáticamente.</p>
            </div>
        </div>

        {{-- Acceso rápido --}}
        <div class="card">
            <div class="card-header"><span class="card-title">⚡ Accesos Rápidos</span></div>
            <div class="p-4 flex flex-col gap-2">
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-ghost btn-sm justify-start gap-2">
                    👩‍🏫 Ver Docentes
                </a>
                <a href="{{ route('admin.archivos.index') }}" class="btn btn-ghost btn-sm justify-start gap-2">
                    📁 Ver Documentos
                </a>
                <button type="button" onclick="openCreateModal()" class="btn btn-primary btn-sm justify-start gap-2">
                    ➕ Nueva Sección
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ── Modal Crear Sección ──────────────────────────────── --}}
<div class="modal-overlay" id="createModal" style="display:none!important">
    <div class="modal-box !max-w-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-slate-100">Nueva Sección</h3>
        </div>
        <form method="POST" action="{{ route('admin.secciones.store') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Nombre de la Sección</label>
                <input type="text" name="nombre" id="createNombre"
                       class="input @error('nombre') border-red-500 @enderror"
                       placeholder="Ej: A, B, C..."
                       value="{{ old('nombre') }}"
                       maxlength="10" autofocus>
                @error('nombre')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1.5">Se guardará en mayúsculas automáticamente.</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCreateModal()" class="btn btn-ghost btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Crear Sección</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal Editar Sección ──────────────────────────────── --}}
<div class="modal-overlay" id="editModal" style="display:none!important">
    <div class="modal-box !max-w-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-full bg-sky-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-slate-100">Editar Sección</h3>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="form-label">Nombre de la Sección</label>
                <input type="text" name="nombre" id="editNombre"
                       class="input"
                       maxlength="10">
                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1.5">Se guardará en mayúsculas automáticamente.</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeEditModal()" class="btn btn-ghost btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ── Modal Crear ────────────────────────────────────────────
    function openCreateModal() {
        document.getElementById('createModal').style.setProperty('display', 'flex', 'important');
        document.getElementById('createNombre').focus();
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.setProperty('display', 'none', 'important');
    }
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) closeCreateModal();
    });

    // ── Modal Editar ───────────────────────────────────────────
    function openEditModal(id, nombre) {
        document.getElementById('editForm').action = `/admin/secciones/${id}`;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editModal').style.setProperty('display', 'flex', 'important');
        document.getElementById('editNombre').focus();
    }
    function closeEditModal() {
        document.getElementById('editModal').style.setProperty('display', 'none', 'important');
    }
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Abrir modal si hay errores de validación al crear
    @if($errors->any() && old('nombre') !== null)
        openCreateModal();
    @endif
</script>
@endsection
