@extends('layouts.admin')

@section('title', 'Editar Docente')
@section('page-title', 'Editar Docente')
@section('breadcrumb', 'Admin / Docentes / ' . $docente->nombre_completo)

@section('content')

<div class="max-w-3xl">
<form method="POST" action="{{ route('admin.docentes.update', $docente) }}">
    @csrf @method('PUT')

    {{-- Datos personales --}}
    <div class="card mb-5">
        <div class="card-header">
            <span class="card-title">👤 Datos Personales</span>
            @if($docente->activo)
                <span class="badge badge-success">● Activo</span>
            @else
                <span class="badge badge-danger">● Inactivo</span>
            @endif
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="form-group mb-0">
                    <label class="form-label" for="nombre">Nombre *</label>
                    <input id="nombre" type="text" name="nombre"
                           value="{{ old('nombre', $docente->nombre) }}"
                           class="input {{ $errors->has('nombre') ? 'input-error' : '' }}" required>
                    @error('nombre') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" for="apellido">Apellido *</label>
                    <input id="apellido" type="text" name="apellido"
                           value="{{ old('apellido', $docente->apellido) }}"
                           class="input {{ $errors->has('apellido') ? 'input-error' : '' }}" required>
                    @error('apellido') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" for="email">Correo Electrónico *</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $docente->email) }}"
                           class="input {{ $errors->has('email') ? 'input-error' : '' }}" required>
                    @error('email') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" for="password">
                        Nueva Contraseña
                        <span class="text-slate-600 normal-case">(vacío = no cambiar)</span>
                    </label>
                    <input id="password" type="password" name="password"
                           class="input {{ $errors->has('password') ? 'input-error' : '' }}"
                           placeholder="••••••••">
                    @error('password') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Asignaciones dinámicas --}}
    @php
        $asignacionesParaMostrar = old('asignaciones')
            ?? $docente->asignaciones->map(fn($a) => [
                'curso_id'   => $a->curso_id,
                'grado_id'   => $a->grado_id,
                'seccion_id' => $a->seccion_id,
            ])->toArray();
    @endphp

    <div class="card mb-5">
        <div class="card-header">
            <span class="card-title">📚 Asignaciones</span>
            <button type="button" id="btnAgregar" class="btn btn-success btn-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar fila
            </button>
        </div>
        <div class="card-body">
            <p class="text-xs text-slate-500 mb-4">
                Al guardar, las asignaciones actuales serán reemplazadas por estas.
            </p>
            <div class="overflow-x-auto rounded-lg border border-slate-700/50">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Curso</th><th>Grado</th><th>Sección</th><th class="w-14 text-center">—</th>
                        </tr>
                    </thead>
                    <tbody id="filasBody">
                        @foreach($asignacionesParaMostrar as $i => $asig)
                        <tr class="fila-asig">
                            <td>
                                <select name="asignaciones[{{ $i }}][curso_id]" class="input py-2 text-xs">
                                    <option value="">Seleccionar</option>
                                    @foreach($cursos as $c)
                                        <option value="{{ $c->id }}" {{ ($asig['curso_id'] ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="asignaciones[{{ $i }}][grado_id]" class="input py-2 text-xs">
                                    <option value="">Seleccionar</option>
                                    @foreach($grados as $g)
                                        <option value="{{ $g->id }}" {{ ($asig['grado_id'] ?? '') == $g->id ? 'selected' : '' }}>{{ $g->nivel->nombre }} — {{ $g->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="asignaciones[{{ $i }}][seccion_id]" class="input py-2 text-xs">
                                    <option value="">Seleccionar</option>
                                    @foreach($secciones as $s)
                                        <option value="{{ $s->id }}" {{ ($asig['seccion_id'] ?? '') == $s->id ? 'selected' : '' }}>Sección {{ $s->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-del">✕</button></td>
                        </tr>
                        @endforeach
                        <tr id="emptyRow" class="{{ count($asignacionesParaMostrar) > 0 ? 'hidden' : '' }}">
                            <td colspan="4" class="py-6 text-center text-xs text-slate-600">Sin asignaciones.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Guardar Cambios
        </button>
        <a href="{{ route('admin.docentes.show', $docente) }}" class="btn btn-ghost">Cancelar</a>
    </div>
</form>
</div>

@endsection

@section('scripts')
<script>
const CURSOS = @json($cursos->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre]));
const GRADOS = @json($grados->map(fn($g) => ['id' => $g->id, 'nombre' => $g->nivel->nombre . ' — ' . $g->nombre]));
const SECCIONES = @json($secciones->map(fn($s) => ['id' => $s->id, 'nombre' => 'Sección ' . $s->nombre]));
let idx = {{ count($asignacionesParaMostrar ?? []) }};

function makeSelect(name, items) {
    return `<select name="${name}" class="input py-2 text-xs"><option value="">Seleccionar</option>${items.map(i=>`<option value="${i.id}">${i.nombre}</option>`).join('')}</select>`;
}

document.getElementById('btnAgregar').addEventListener('click', () => {
    const tr = document.createElement('tr');
    tr.className = 'fila-asig';
    tr.innerHTML = `<td>${makeSelect(`asignaciones[${idx}][curso_id]`,CURSOS)}</td><td>${makeSelect(`asignaciones[${idx}][grado_id]`,GRADOS)}</td><td>${makeSelect(`asignaciones[${idx}][seccion_id]`,SECCIONES)}</td><td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-del">✕</button></td>`;
    document.getElementById('emptyRow').insertAdjacentElement('beforebegin', tr);
    tr.querySelector('.btn-del').addEventListener('click', () => removeFila(tr));
    idx++; checkEmpty();
});

function removeFila(tr) { tr.style.opacity='0'; tr.style.transition='opacity .2s'; setTimeout(()=>{tr.remove();checkEmpty();},200); }
function checkEmpty() { document.getElementById('emptyRow').classList.toggle('hidden', document.querySelectorAll('.fila-asig').length > 0); }
document.querySelectorAll('.btn-del').forEach(b => b.addEventListener('click', () => removeFila(b.closest('tr'))));
checkEmpty();
</script>
@endsection
