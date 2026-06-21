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

{{-- ══════════════════════════════════════════════════════════ --}}
{{-- GRID: Datos Personales + Asignaciones                      --}}
{{-- ══════════════════════════════════════════════════════════ --}}
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
                    Agregar cursos de un Grado
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
                    Al seleccionar un grado se añaden automáticamente todos sus cursos.
                    Al guardar, las asignaciones actuales serán <strong>reemplazadas</strong> por las de abajo.
                </p>
            </div>

            {{-- Tabla de asignaciones --}}
            <div class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700/50">
                <table class="table">
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

// DNI, nombre y apellido son de solo lectura — no hay lógica de verificación de DNI en edición.

// ─── Tabla de asignaciones ───────────────────────────────────────────────────
const CURSOS = {!! json_encode($cursos->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre])) !!};
const GRADOS = {!! json_encode($grados->map(fn($g) => [
    'id'     => $g->id,
    'nombre' => $g->nivel->nombre . ' — ' . $g->nombre,
    'nivel'  => $g->nivel->nombre,
    'grado'  => $g->nombre
])) !!};
const SECCION_UNICA_ID = {{ $seccionUnica->id }};
let idx = {{ count($asignacionesParaMostrar ?? []) }};

// Filtra cursos válidos para el nivel/grado
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

// Crea una fila grado+curso
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

// Agrega automáticamente todos los cursos del grado seleccionado
function agregarCursosDeGrado() {
    const sel     = document.getElementById('selectGradoAgregar');
    const gradoId = sel.value;
    const opt     = sel.options[sel.selectedIndex];
    if (!gradoId) return;

    const nivel  = opt.dataset.nivel;
    const grado  = opt.dataset.grado;
    const nombre = opt.text;
    const cursos = cursosParaNivel(nivel, grado);

    if (!cursos.length) {
        alert('No hay cursos registrados para este nivel.');
        return;
    }

    // Limpiar toda la tabla para reemplazar los cursos anteriores
    document.querySelectorAll('.fila-asig').forEach(tr => tr.remove());

    const empty = document.getElementById('emptyRow');
    cursos.forEach(c => {
        empty.insertAdjacentElement('beforebegin', crearFila(gradoId, nombre, c.nombre));
    });

    checkEmpty();
    sel.value = '';
}

document.getElementById('selectGradoAgregar').addEventListener('change', agregarCursosDeGrado);

function removeFila(tr) {
    tr.style.opacity = '0';
    tr.style.transition = 'opacity .15s';
    setTimeout(() => { tr.remove(); checkEmpty(); }, 150);
}

function checkEmpty() {
    document.getElementById('emptyRow').classList.toggle('hidden', document.querySelectorAll('.fila-asig').length > 0);
}

document.querySelectorAll('.btn-del').forEach(b => b.addEventListener('click', () => removeFila(b.closest('tr'))));
checkEmpty();

const style = document.createElement('style');
style.textContent = '@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }';
document.head.appendChild(style);
</script>
@endsection


