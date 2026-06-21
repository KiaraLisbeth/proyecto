@extends('layouts.admin')

@section('title', 'Nuevo Docente')
@section('page-title', 'Nuevo Docente')
@section('breadcrumb', 'Admin / Docentes / Nuevo')
@section('topbar-actions')
    <a href="{{ route('admin.docentes.index') }}" class="btn btn-ghost btn-sm">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver
    </a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.docentes.store') }}" id="formDocente">
@csrf

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- GRID: Datos Personales + Asignaciones                      --}}
{{-- ══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 xl:grid-cols-[420px_1fr] gap-5 items-start">

    {{-- ── Datos Personales ────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">👤 Datos Personales</span>
        </div>
        <div class="card-body space-y-4">

            {{-- DNI — primero y con verificación automática --}}
            <div class="form-group mb-0">
                <label class="form-label" for="dni">DNI *</label>
                <div class="relative">
                    <input
                        id="dni"
                        type="text"
                        name="dni"
                        value="{{ old('dni') }}"
                        class="input pr-10 tracking-widest font-mono {{ $errors->has('dni') ? 'input-error' : '' }}"
                        placeholder="12345678"
                        maxlength="8"
                        inputmode="numeric"
                        autocomplete="off"
                        required>
                    {{-- Spinner --}}
                    <div id="dniSpinner" class="absolute inset-y-0 right-3 flex items-center hidden pointer-events-none">
                        <svg class="animate-spin w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    {{-- Check --}}
                    <div id="dniCheck" class="absolute inset-y-0 right-3 flex items-center hidden pointer-events-none">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    {{-- Error X --}}
                    <div id="dniError" class="absolute inset-y-0 right-3 flex items-center hidden pointer-events-none">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                @error('dni')
                    <p class="error-msg">⚠ {{ $message }}</p>
                @else
                    <div id="dniMsg" class="hidden mt-1.5 text-xs px-3 py-2 rounded-lg"></div>
                @enderror
            </div>

            {{-- Nombre --}}
            <div class="form-group mb-0">
                <label class="form-label" for="nombre">Nombre *</label>
                <input id="nombre" type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       class="input {{ $errors->has('nombre') ? 'input-error' : '' }}"
                       placeholder="Ej: María Elena" required>
                @error('nombre') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>

            {{-- Apellido --}}
            <div class="form-group mb-0">
                <label class="form-label" for="apellido">Apellido *</label>
                <input id="apellido" type="text" name="apellido"
                       value="{{ old('apellido') }}"
                       class="input {{ $errors->has('apellido') ? 'input-error' : '' }}"
                       placeholder="Ej: García Quispe" required>
                @error('apellido') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>

            {{-- Usuario --}}
            <div class="form-group mb-0">
                <label class="form-label" for="username">Usuario *</label>
                <input id="username" type="text" name="username"
                       value="{{ old('username') }}"
                       class="input uppercase {{ $errors->has('username') ? 'input-error' : '' }}"
                       placeholder="Ej: MGARCIAQ" required>
                @error('username') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>

            {{-- Contraseña --}}
            <div class="form-group mb-0">
                <label class="form-label" for="password">
                    Contraseña *
                    <span class="text-xs text-slate-400 font-normal">(8 caracteres)</span>
                </label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                           class="input pr-10 {{ $errors->has('password') ? 'input-error' : '' }}"
                           placeholder="••••••••" minlength="8" maxlength="8" required>
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
                    Crear Docente
                </button>
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>

        </div>
    </div>

    {{-- ── Asignaciones de Cursos ──────────────────────────────────── --}}
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

            {{-- Tabla de asignaciones generadas --}}
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
                        @if(old('asignaciones'))
                            @foreach(old('asignaciones') as $i => $asig)
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
                        @endif
                        <tr id="emptyRow" class="{{ old('asignaciones') ? 'hidden' : '' }}">
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

// ─── Verificación de DNI duplicado (solo base de datos local) ───────────────
const dniInput      = document.getElementById('dni');
const spinner       = document.getElementById('dniSpinner');
const checkIcon     = document.getElementById('dniCheck');
const errorIcon     = document.getElementById('dniError');
const dniMsg        = document.getElementById('dniMsg');
const nombreInput   = document.getElementById('nombre');
const apellidoInput = document.getElementById('apellido');
const DNI_URL       = "{{ route('admin.docentes.buscarDni') }}";
let dniTimer = null;

// Si el usuario edita nombre/apellido a mano, dejamos de pisarlo en futuras
// búsquedas de DNI (un input por script no dispara 'input', solo el tecleo real).
nombreInput.addEventListener('input', () => { nombreInput.dataset.autofilled = 'false'; });
apellidoInput.addEventListener('input', () => { apellidoInput.dataset.autofilled = 'false'; });

function resetDniIcons() {
    spinner.classList.add('hidden');
    checkIcon.classList.add('hidden');
    errorIcon.classList.add('hidden');
    if (dniMsg) {
        dniMsg.classList.add('hidden');
        dniMsg.className = 'hidden mt-1.5 text-xs px-3 py-2 rounded-lg';
        dniMsg.innerHTML = '';
    }
    dniInput.classList.remove('input-error');
}

async function verificarDni(dni) {
    resetDniIcons();
    spinner.classList.remove('hidden');

    try {
        const res  = await fetch(`${DNI_URL}?dni=${encodeURIComponent(dni)}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        spinner.classList.add('hidden');

        if (res.status === 409) {
            // DNI ya registrado → mostrar aviso de error
            dniInput.classList.add('input-error');
            errorIcon.classList.remove('hidden');
            if (dniMsg) {
                dniMsg.className = 'mt-1.5 text-xs px-3 py-2 rounded-lg bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400 border border-red-200 dark:border-red-500/30';
                dniMsg.innerHTML = `⚠ ${data.mensaje}`;
            }
        } else if (res.ok) {
            // DNI disponible
            checkIcon.classList.remove('hidden');

            // Autocompletar nombre/apellido si RENIEC devolvió datos.
            // Se sobreescribe (incluso limpiando lo del DNI anterior) salvo
            // que el usuario ya haya editado el campo a mano.
            let autocompletado = false;
            if (nombreInput.dataset.autofilled !== 'false') {
                nombreInput.value = data.nombre || '';
                if (data.nombre) { nombreInput.dataset.autofilled = 'true'; autocompletado = true; }
            }
            if (apellidoInput.dataset.autofilled !== 'false') {
                apellidoInput.value = data.apellido || '';
                if (data.apellido) { apellidoInput.dataset.autofilled = 'true'; autocompletado = true; }
            }

            if (dniMsg) {
                dniMsg.className = 'mt-1.5 text-xs px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30';
                dniMsg.innerHTML = autocompletado
                    ? '✓ DNI disponible. Nombre y apellido autocompletados, verifícalos.'
                    : '✓ DNI disponible. Completa los datos del docente.';
            }
        }
    } catch (e) {
        spinner.classList.add('hidden');
        // Error de red — no bloquear nada, el servidor validará igual
    }
}

dniInput.addEventListener('input', function () {
    // Solo dígitos
    this.value = this.value.replace(/\D/g, '').slice(0, 8);
    resetDniIcons();
    clearTimeout(dniTimer);
    if (this.value.length === 8) {
        dniTimer = setTimeout(() => verificarDni(this.value), 400);
    }
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
let idx = {{ old('asignaciones') ? count(old('asignaciones')) : 0 }};

// Filtra los cursos válidos para el nivel/grado
function cursosParaNivel(nivel, grado) {
    const n = (s) => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const nv = n(nivel), gd = n(grado);

    let list = [];
    if (nv === 'inicial') {
        // Cursos específicos para Inicial (3, 4 y 5 años)
        const cursosInicial = [
            'comunicacion',
            'matematica',
            'personal social',
            'ciencia y tecnologia',
            'arte y cultura',
            'educacion fisica',
            'educacion cristiana'
        ];
        list = CURSOS.filter(c => {
            const cn = n(c.nombre);
            return cursosInicial.includes(cn);
        });
    } else if (nv === 'primaria') {
        // Cursos para Primaria (1ro a 6to)
        list = CURSOS.filter(c => {
            const cn = n(c.nombre);
            // Excluir variantes antiguas que ahora usan otros nombres
            if (cn === 'ciencias naturales' || cn === 'educacion religiosa') return false;
            if (cn.includes('historia') || cn.includes('geografia') || cn.includes('economia')) return false;
            if (cn.includes('ingles')) {
                // Inglés solo de 4to a 6to de Primaria
                return !['1ro','2do','3ro'].some(x => gd.includes(x));
            }
            return true;
        });
    } else {
        // Secundaria
        list = CURSOS.filter(c => {
            const cn = n(c.nombre);
            if (cn.includes('personal social')) return false;
            if (cn === 'ciencia y tecnologia' || cn === 'educacion cristiana') return false;
            return true;
        });
    }

    // Eliminar duplicados agrupando por nombre normalizado (ej: "Matemática" y "Matematica")
    // Dando preferencia a la versión que tiene tilde/correcta
    const mapa = {};
    list.forEach(c => {
        const cn = n(c.nombre);
        if (!mapa[cn]) {
            mapa[cn] = c;
        } else {
            const tieneTilde = /[áéíóúÁÉÍÓÚñÑ]/.test(c.nombre);
            const anteriorTieneTilde = /[áéíóúÁÉÍÓÚñÑ]/.test(mapa[cn].nombre);
            if (tieneTilde && !anteriorTieneTilde) {
                mapa[cn] = c;
            }
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
    const filas = document.querySelectorAll('.fila-asig');
    for (let fila of filas) {
        const gInput = fila.querySelector('input[name*="[grado_id]"]');
        const cInput = fila.querySelector('input[name*="[curso_nombre]"]');
        if (gInput && cInput && gInput.value == gradoId && cInput.value.toLowerCase() === cursoNombre.toLowerCase()) {
            return true;
        }
    }
    return false;
}

// Agrega automáticamente todas las filas de cursos para el grado seleccionado
// (usado para Inicial y Primaria, donde el docente dicta todos los cursos del grado)
function agregarCursosAutomatico(gradoId, nombre, cursos) {
    // Limpiar toda la tabla para reemplazar los cursos anteriores
    document.querySelectorAll('.fila-asig').forEach(tr => tr.remove());

    const empty = document.getElementById('emptyRow');
    cursos.forEach(c => {
        const tr = crearFila(gradoId, nombre, c.nombre);
        empty.insertAdjacentElement('beforebegin', tr);
    });

    checkEmpty();
}

// ─── Selector de cursos específicos para Secundaria ──────────────────────────
const cursosSecundariaBox = document.getElementById('cursosSecundariaBox');
const cursosCheckboxList  = document.getElementById('cursosCheckboxList');
let secundariaActual = null; // { gradoId, gradoNombre }
let lastNivelSeleccionado = null; // evita mezclar cursos de niveles distintos en la tabla

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

    document.querySelectorAll('.chk-curso-sec:checked').forEach(chk => {
        if (!existeAsignacion(gradoId, chk.value)) {
            const tr = crearFila(gradoId, gradoNombre, chk.value);
            empty.insertAdjacentElement('beforebegin', tr);
        }
    });

    checkEmpty();
    cursosSecundariaBox.classList.add('hidden');
    document.getElementById('selectGradoAgregar').value = '';
});

// Al elegir un grado: Secundaria muestra el selector de cursos, los demás niveles autocompletan
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
        // Si venimos de otro nivel (Inicial/Primaria), limpiar lo que quedó antes
        if (lastNivelSeleccionado !== 'secundaria') {
            document.querySelectorAll('.fila-asig').forEach(tr => tr.remove());
            checkEmpty();
        }
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

// Botones eliminar de filas ya existentes (old() tras validación)
document.querySelectorAll('.btn-del').forEach(btn => {
    btn.addEventListener('click', () => removeFila(btn.closest('tr')));
});

checkEmpty();

const style = document.createElement('style');
style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }';
document.head.appendChild(style);
</script>
@endsection
