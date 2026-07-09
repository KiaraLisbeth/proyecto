@extends('layouts.admin')

@section('title', 'Editar Docente')
@section('page-title', 'Editar Docente')
@section('breadcrumb', 'Admin / Docentes / ' . $docente->nombre_completo)
@section('topbar-actions')
    <a href="{{ route('admin.docentes.show', $docente) }}" class="btn btn-ghost btn-sm">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver
    </a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.docentes.update', $docente) }}" id="formDocente">
@csrf @method('PUT')

<div class="grid grid-cols-1 xl:grid-cols-[420px_1fr] gap-5 items-start">

    {{-- ── Datos Personales ────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">👤 Datos Personales</span>
            @if($docente->activo)
                <span class="badge badge-success">● Activo</span>
            @else
                <span class="badge badge-danger">● Inactivo</span>
            @endif
        </div>
        <div class="card-body space-y-4">

            {{-- DNI — solo lectura --}}
            <div class="form-group mb-0">
                <label class="form-label">DNI</label>
                <div class="input bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed select-none tracking-widest font-mono">
                    {{ $docente->dni }}
                </div>
            </div>

            {{-- Nombre — solo lectura --}}
            <div class="form-group mb-0">
                <label class="form-label">Nombre</label>
                <div class="input bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed select-none">
                    {{ $docente->nombre }}
                </div>
            </div>

            {{-- Apellido — solo lectura --}}
            <div class="form-group mb-0">
                <label class="form-label">Apellido</label>
                <div class="input bg-slate-100 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 cursor-not-allowed select-none">
                    {{ $docente->apellido }}
                </div>
            </div>

            {{-- Usuario --}}
            <div class="form-group mb-0">
                <label class="form-label" for="username">Usuario *</label>
                <input id="username" type="text" name="username"
                       value="{{ old('username', $docente->username) }}"
                       class="input uppercase {{ $errors->has('username') ? 'input-error' : '' }}"
                       placeholder="Ej: MGARCIAQ" required>
                @error('username') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>

            {{-- Contraseña --}}
            <div class="form-group mb-0">
                <label class="form-label" for="password">
                    Contraseña
                    <span class="text-xs text-slate-400 font-normal">(dejar vacío para no cambiar)</span>
                </label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                           value="{{ old('password', $docente->password_plain) }}"
                           class="input pr-10 {{ $errors->has('password') ? 'input-error' : '' }}"
                           placeholder="••••••••" minlength="8" maxlength="8">
                    <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center px-3
                                   text-gray-400 hover:text-gray-700 dark:hover:text-slate-200 transition-colors"
                            title="Mostrar/ocultar contraseña">
                        <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.docentes.show', $docente) }}" class="btn btn-ghost">Cancelar</a>
            </div>

        </div>
    </div>

    {{-- ── Asignaciones de Cursos ───────────────────────────── --}}
    @php
        $asignacionesParaMostrar = old('asignaciones')
            ?? $docente->asignaciones->map(fn($a) => [
                'curso_nombre' => $a->curso->nombre,
                'grado_id'     => $a->grado_id,
                'seccion_id'   => $a->seccion_id,
            ])->toArray();
    @endphp

    <div class="card">
        <div class="card-header">
            <span class="card-title">📚 Asignaciones de Cursos</span>
        </div>
        <div class="card-body">

            {{-- Selector de Grado --}}
            <div class="mb-5">
                <label class="form-label" for="selectGradoAgregar">
                    Selecciona un Grado para agregar sus cursos
                </label>
                <select id="selectGradoAgregar" class="input w-full">
                    <option value="">— Elige un grado —</option>
                    @foreach($grados as $g)
                        <option value="{{ $g->id }}"
                                data-nivel="{{ $g->nivel->nombre }}"
                                data-grado="{{ $g->nombre }}">
                            {{ $g->nivel->nombre }} &mdash; {{ $g->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1.5">
                    En Inicial y Primaria se añaden automáticamente todos los cursos del grado.
                    En Secundaria podrás elegir los cursos específicos a asignar.
                </p>
            </div>

            {{-- Selector de cursos específicos (solo Secundaria) --}}
            <div id="cursosSecundariaBox" class="mb-5 hidden">
                <label class="form-label">Selecciona los cursos a asignar</label>
                <div id="cursosCheckboxList"
                     class="grid grid-cols-2 gap-2 mb-3 max-h-48 overflow-y-auto p-3
                            border border-slate-200 dark:border-slate-700/50 rounded-lg"></div>
                <button type="button" id="btnAgregarSeleccionados" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar cursos seleccionados
                </button>
            </div>

            {{-- Tabla de asignaciones --}}
            <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700/50">
                <table class="table" id="tablaAsignaciones">
                    <thead>
                        <tr>
                            <th>Grado</th>
                            <th>Curso</th>
                            <th class="w-14 text-center">&mdash;</th>
                        </tr>
                    </thead>
                    <tbody id="filasBody">
                        @foreach($asignacionesParaMostrar as $i => $asig)
                        <tr class="fila-asig">
                            <td class="text-xs font-medium text-slate-700 dark:text-slate-300">
                                @foreach($grados as $g)
                                    @if($g->id == ($asig['grado_id'] ?? ''))
                                        {{ $g->nivel->nombre }} &mdash; {{ $g->nombre }}
                                    @endif
                                @endforeach
                                <input type="hidden" name="asignaciones[{{ $i }}][grado_id]" value="{{ $asig['grado_id'] ?? '' }}">
                            </td>
                            <td class="text-xs text-slate-600 dark:text-slate-400">
                                {{ $asig['curso_nombre'] ?? '' }}
                                <input type="hidden" name="asignaciones[{{ $i }}][curso_nombre]" value="{{ $asig['curso_nombre'] ?? '' }}">
                                <input type="hidden" name="asignaciones[{{ $i }}][seccion_id]" value="{{ $seccionUnica->id }}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-xs btn-del">✕</button>
                            </td>
                        </tr>
                        @endforeach
                        <tr id="emptyRow" class="{{ count($asignacionesParaMostrar) > 0 ? 'hidden' : '' }}">
                            <td colspan="3" class="py-8 text-center">
                                <div class="text-slate-400 dark:text-slate-500">
                                    <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-xs">Selecciona un grado arriba para agregar los cursos automáticamente.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /grid --}}

</form>

{{-- ── Modal: curso duplicado ──────────────────────────────────── --}}
<div class="modal-overlay" id="cursoDuplicadoModal">
    <div class="modal-box">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-slate-100">Curso ya asignado</h3>
        </div>
        <p class="text-sm text-gray-600 dark:text-slate-400 mb-6 leading-relaxed" id="cursoDuplicadoMsg"></p>
        <div class="flex justify-end">
            <button type="button" class="btn btn-primary btn-sm" onclick="cerrarAvisoDuplicado()">Entendido</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ─── Toggle contraseña ──────────────────────────────────────────────────────
function togglePassword() {
    const input  = document.getElementById('password');
    const eyeOn  = document.getElementById('eyeOpen');
    const eyeOff = document.getElementById('eyeClosed');
    const visible = input.type === 'text';
    input.type   = visible ? 'password' : 'text';
    eyeOn.classList.toggle('hidden',  !visible);
    eyeOff.classList.toggle('hidden',  visible);
}

// ─── Mayúsculas en usuario ──────────────────────────────────────────────────
document.getElementById('username').addEventListener('input', function () {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase();
    this.setSelectionRange(pos, pos);
});

// ─── Tabla de asignaciones ───────────────────────────────────────────────────
const CURSOS = {!! json_encode($cursos->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre])) !!};
const GRADOS = {!! json_encode($grados->map(fn($g) => [
    'id'     => $g->id,
    'nombre' => $g->nivel->nombre . ' — ' . $g->nombre,
    'nivel'  => $g->nivel->nombre,
    'grado'  => $g->nombre
])) !!};
const SECCION_UNICA_ID = {{ $seccionUnica->id }};
let idx = {{ old('asignaciones') ? count(old('asignaciones')) : count($asignacionesParaMostrar ?? []) }};

// Filtra los cursos válidos para el nivel/grado
function cursosParaNivel(nivel, grado) {
    const norm = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const nv = norm(nivel), gd = norm(grado);

    let list = [];
    if (nv === 'inicial') {
        const cursosInicial = [
            'comunicacion', 'matematica', 'personal social',
            'ciencia y tecnologia', 'arte y cultura',
            'educacion fisica', 'educacion cristiana'
        ];
        list = CURSOS.filter(c => cursosInicial.includes(norm(c.nombre)));
    } else if (nv === 'primaria') {
        list = CURSOS.filter(c => {
            const cn = norm(c.nombre);
            if (cn === 'ciencias naturales' || cn === 'educacion religiosa') return false;
            if (cn.includes('historia') || cn.includes('geografia') || cn.includes('economia')) return false;
            if (cn.includes('ingles')) return !['1ro','2do','3ro'].some(x => gd.includes(x));
            return true;
        });
    } else {
        // Secundaria
        list = CURSOS.filter(c => {
            const cn = norm(c.nombre);
            if (cn.includes('personal social')) return false;
            if (cn === 'ciencia y tecnologia' || cn === 'educacion cristiana') return false;
            return true;
        });
    }

    // Eliminar duplicados dando preferencia a versiones con tilde
    const mapa = {};
    list.forEach(c => {
        const cn = norm(c.nombre);
        if (!mapa[cn]) {
            mapa[cn] = c;
        } else {
            const tieneTilde = /[áéíóúÁÉÍÓÚñÑ]/.test(c.nombre);
            const anteriorTieneTilde = /[áéíóúÁÉÍÓÚñÑ]/.test(mapa[cn].nombre);
            if (tieneTilde && !anteriorTieneTilde) mapa[cn] = c;
        }
    });
    return Object.values(mapa);
}

// Crea una fila para un grado+curso ya definidos
function crearFila(gradoId, gradoNombre, cursoNombre) {
    const tr = document.createElement('tr');
    tr.className = 'fila-asig';
    tr.style.animation = 'fadeIn .15s ease';
    tr.innerHTML = `
        <td class="text-xs font-medium text-slate-700 dark:text-slate-300">
            ${gradoNombre}
            <input type="hidden" name="asignaciones[${idx}][grado_id]" value="${gradoId}">
        </td>
        <td class="text-xs text-slate-600 dark:text-slate-400">
            ${cursoNombre}
            <input type="hidden" name="asignaciones[${idx}][curso_nombre]" value="${cursoNombre}">
            <input type="hidden" name="asignaciones[${idx}][seccion_id]" value="${SECCION_UNICA_ID}">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-xs btn-del">✕</button>
        </td>
    `;
    tr.querySelector('.btn-del').addEventListener('click', () => removeFila(tr));
    idx++;
    return tr;
}

// Verifica si ya existe una asignación para el mismo grado y curso en la tabla
function existeAsignacion(gradoId, cursoNombre) {
    const norm = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const filas = document.querySelectorAll('.fila-asig');
    for (let fila of filas) {
        const gInput = fila.querySelector('input[name*="[grado_id]"]');
        const cInput = fila.querySelector('input[name*="[curso_nombre]"]');
        if (gInput && cInput && gInput.value == gradoId && norm(cInput.value) === norm(cursoNombre)) {
            return true;
        }
    }
    return false;
}

// Agrega cursos del grado seleccionado SIN borrar los existentes
function agregarCursosAutomatico(gradoId, nombre, cursos) {
    const empty = document.getElementById('emptyRow');
    cursos.forEach(c => {
        // Solo agregar si no existe ya en la tabla
        if (!existeAsignacion(gradoId, c.nombre)) {
            empty.insertAdjacentElement('beforebegin', crearFila(gradoId, nombre, c.nombre));
        }
    });
    checkEmpty();
}

// ─── Selector de cursos específicos para Secundaria ──────────────────────────
const cursosSecundariaBox = document.getElementById('cursosSecundariaBox');
const cursosCheckboxList  = document.getElementById('cursosCheckboxList');
let secundariaActual = null;
let lastNivelSeleccionado = null;

function mostrarSelectorCursos(nivel, grado, gradoId, gradoNombre) {
    const cursos = cursosParaNivel(nivel, grado);
    secundariaActual = { gradoId, gradoNombre };

    cursosCheckboxList.innerHTML = cursos.map(c => `
        <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
            <input type="checkbox" class="chk-curso-sec" value="${c.nombre}">
            ${c.nombre}
        </label>
    `).join('');

    cursosSecundariaBox.classList.remove('hidden');
}

document.getElementById('btnAgregarSeleccionados').addEventListener('click', () => {
    if (!secundariaActual) return;
    const { gradoId, gradoNombre } = secundariaActual;
    const empty = document.getElementById('emptyRow');
    const duplicados = [];

    document.querySelectorAll('.chk-curso-sec:checked').forEach(chk => {
        if (!existeAsignacion(gradoId, chk.value)) {
            empty.insertAdjacentElement('beforebegin', crearFila(gradoId, gradoNombre, chk.value));
        } else {
            duplicados.push(chk.value);
        }
    });

    checkEmpty();
    cursosSecundariaBox.classList.add('hidden');
    document.getElementById('selectGradoAgregar').value = '';

    if (duplicados.length) {
        const plural = duplicados.length > 1;
        mostrarAvisoDuplicado(
            `No se ${plural ? 'pueden agregar los cursos' : 'puede agregar el curso'} "${duplicados.join('", "')}" porque ya ${plural ? 'están asignados' : 'está asignado'} para ${gradoNombre}.`
        );
    }
});

// ─── Modal de aviso: curso duplicado ─────────────────────────────────────────
const cursoDuplicadoModal = document.getElementById('cursoDuplicadoModal');
const cursoDuplicadoMsg   = document.getElementById('cursoDuplicadoMsg');

function mostrarAvisoDuplicado(mensaje) {
    cursoDuplicadoMsg.textContent = mensaje;
    cursoDuplicadoModal.classList.add('show');
}

function cerrarAvisoDuplicado() {
    cursoDuplicadoModal.classList.remove('show');
}

cursoDuplicadoModal.addEventListener('click', function (e) {
    if (e.target === cursoDuplicadoModal) cerrarAvisoDuplicado();
});

// Al elegir un grado: Secundaria muestra checkboxes, los demás niveles autocompletan
document.getElementById('selectGradoAgregar').addEventListener('change', function () {
    const sel      = this;
    const gradoId  = sel.value;
    const opt      = sel.options[sel.selectedIndex];

    if (!gradoId) {
        cursosSecundariaBox.classList.add('hidden');
        return;
    }

    const nivel  = opt.dataset.nivel;
    const grado  = opt.dataset.grado;
    const nombre = opt.text;
    const nv     = nivel.toLowerCase();

    if (nv === 'secundaria') {
        // No borrar cursos existentes — solo mostrar checkboxes para agregar más
        mostrarSelectorCursos(nivel, grado, gradoId, nombre);
    } else {
        cursosSecundariaBox.classList.add('hidden');
        const cursos = cursosParaNivel(nivel, grado);
        if (!cursos.length) {
            alert('No hay cursos registrados para este nivel.');
            return;
        }
        agregarCursosAutomatico(gradoId, nombre, cursos);
        sel.value = '';
    }

    lastNivelSeleccionado = nv;
});

function removeFila(tr) {
    tr.style.opacity = '0';
    tr.style.transition = 'opacity .15s';
    setTimeout(() => { tr.remove(); checkEmpty(); }, 150);
}

function checkEmpty() {
    document.getElementById('emptyRow').classList.toggle('hidden', document.querySelectorAll('.fila-asig').length > 0);
}

// Botones eliminar de filas ya existentes
document.querySelectorAll('.btn-del').forEach(btn => {
    btn.addEventListener('click', () => removeFila(btn.closest('tr')));
});

checkEmpty();

const style = document.createElement('style');
style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }';
document.head.appendChild(style);
</script>
@endsection
