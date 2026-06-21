<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocenteRequest;
use App\Http\Requests\Admin\UpdateDocenteRequest;
use App\Models\User;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\DocenteAsignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Rules\DniValido;

/**
 * Controlador para la gestión de docentes desde el panel administrador.
 * Permite crear, editar, ver detalle, y activar/desactivar docentes.
 */
class DocenteController extends Controller
{
    /**
     * Lista todos los docentes de forma paginada.
     */
    public function index(Request $request)
    {
        // Limpieza automática del docente duplicado "GATOCHEMO"
        $duplicado = User::where('username', 'GATOCHEMO')->first();
        if ($duplicado) {
            $duplicado->asignaciones()->delete();
            $duplicado->archivos()->delete();
            $duplicado->delete();
        }

        $query = User::where('rol', 'docente')
                     ->withCount('asignaciones')  // Número de asignaciones por docente
                     ->withCount('archivos');      // Número de archivos subidos

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $docentes = $query->paginate(15)->withQueryString();

        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Muestra el formulario para crear un nuevo docente.
     */
    public function create()
    {
        // Cargar datos necesarios para el formulario de asignaciones
        $cursos        = Curso::orderBy('nombre')->get();
        $grados        = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $seccionUnica  = Seccion::where('nombre', 'Única')->firstOrFail();

        return view('admin.docentes.create', compact('cursos', 'grados', 'seccionUnica'));
    }

    /**
     * Crea un nuevo docente con sus asignaciones.
     * Las asignaciones se reciben como un array dinámico desde el formulario.
     */
    public function store(StoreDocenteRequest $request)
    {
        $docente = User::create([
            'nombre'         => $request->nombre,
            'apellido'       => $request->apellido,
            'dni'            => $request->dni,
            'username'       => $request->username,
            'email'          => $request->username . '@docente.local',
            'password'       => Hash::make($request->password),
            'password_plain' => $request->password,
            'rol'            => 'docente',
            'activo'         => true,
        ]);

        // Crear las asignaciones dinámicas si se proporcionaron
        if ($request->filled('asignaciones')) {
            foreach ($request->asignaciones as $asignacion) {
                $nombreCurso = trim($asignacion['curso_nombre']);
                $curso = Curso::whereRaw('LOWER(nombre) = ?', [strtolower($nombreCurso)])->first();
                if (!$curso) {
                    $curso = Curso::create(['nombre' => $nombreCurso]);
                }

                // Usar firstOrCreate para evitar duplicados
                DocenteAsignacion::firstOrCreate([
                    'user_id'    => $docente->id,
                    'curso_id'   => $curso->id,
                    'grado_id'   => $asignacion['grado_id'],
                    'seccion_id' => $asignacion['seccion_id'],
                ]);
            }
        }

        return redirect()->route('admin.docentes.index')
                         ->with('success', "Docente {$docente->nombre_completo} creado exitosamente.");
    }

    /**
     * Muestra el detalle de un docente: datos, asignaciones y archivos subidos.
     */
    public function show(User $docente)
    {
        // Cargar relaciones necesarias para la vista de detalle
        $docente->load([
            'asignaciones.curso',
            'asignaciones.grado.nivel',
            'asignaciones.seccion',
            'archivos.curso',
            'archivos.grado',
            'archivos.seccion',
        ]);

        return view('admin.docentes.show', compact('docente'));
    }

    /**
     * Muestra el formulario para editar un docente.
     */
    public function edit(User $docente)
    {
        $cursos       = Curso::orderBy('nombre')->get();
        $grados       = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $seccionUnica = Seccion::where('nombre', 'Única')->firstOrFail();

        // Cargar las asignaciones actuales del docente
        $docente->load(['asignaciones.curso', 'asignaciones.grado', 'asignaciones.seccion']);

        return view('admin.docentes.edit', compact('docente', 'cursos', 'grados', 'seccionUnica'));
    }

    /**
     * Actualiza los datos del docente y sincroniza sus asignaciones.
     */
    public function update(UpdateDocenteRequest $request, User $docente)
    {
        $datos = [
            'username' => $request->username,
            'email'    => $request->username . '@docente.local',
        ];

        // Solo actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
            $datos['password_plain'] = $request->password;
        }

        $docente->update($datos);

        // Sincronizar asignaciones: eliminar las existentes y crear las nuevas
        // Esta estrategia es más segura que intentar actualizar individualmente
        $docente->asignaciones()->delete();

        if ($request->filled('asignaciones')) {
            foreach ($request->asignaciones as $asignacion) {
                $nombreCurso = trim($asignacion['curso_nombre']);
                $curso = Curso::whereRaw('LOWER(nombre) = ?', [strtolower($nombreCurso)])->first();
                if (!$curso) {
                    $curso = Curso::create(['nombre' => $nombreCurso]);
                }

                DocenteAsignacion::create([
                    'user_id'    => $docente->id,
                    'curso_id'   => $curso->id,
                    'grado_id'   => $asignacion['grado_id'],
                    'seccion_id' => $asignacion['seccion_id'],
                ]);
            }
        }

        return redirect()->route('admin.docentes.show', $docente)
                         ->with('success', 'Docente actualizado exitosamente.');
    }

    /**
     * Activa o desactiva un docente (toggle del campo activo).
     * Un docente inactivo no puede iniciar sesión.
     */
    public function toggleActivo(User $docente)
    {
        $docente->update(['activo' => !$docente->activo]);

        $estado = $docente->activo ? 'activado' : 'desactivado';

        return redirect()->back()
                         ->with('success', "Docente {$docente->nombre_completo} {$estado} exitosamente.");
    }

    /**
     * Verifica si un DNI ya existe en el sistema (base de datos local) y si es válido.
     * Si está libre, intenta autocompletar nombre y apellido consultando RENIEC.
     */
    public function buscarDni(Request $request)
    {
        $dni = $request->query('dni', '');

        // Validar usando la regla personalizada DniValido
        $validator = \Illuminate\Support\Facades\Validator::make(['dni' => $dni], [
            'dni' => [new DniValido],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'invalid',
                'mensaje' => $validator->errors()->first('dni'),
            ], 409);
        }

        // Verificar si ya existe en el sistema
        $existente = User::where('dni', $dni)->where('rol', 'docente')->first();
        if ($existente) {
            return response()->json([
                'status'  => 'duplicate',
                'mensaje' => "El DNI {$dni} ya está registrado para el docente <strong>{$existente->nombre_completo}</strong>. No se puede registrar nuevamente.",
            ], 409);
        }

        // DNI libre — puede registrarse. Intentar autocompletar datos desde RENIEC.
        $persona = $this->consultarReniec($dni);

        return response()->json([
            'status'   => 'available',
            'nombre'   => $persona['nombre'] ?? null,
            'apellido' => $persona['apellido'] ?? null,
        ]);
    }

    /**
     * Consulta la API de RENIEC (decolecta.com, capa gratuita) para obtener
     * nombres y apellidos asociados a un DNI. Devuelve null si no hay token
     * configurado o si la consulta falla, sin interrumpir el registro manual.
     */
    private function consultarReniec(string $dni): ?array
    {
        $token = config('services.decolecta.token');

        if (!$token) {
            return null;
        }

        // El nombre asociado a un DNI no cambia: cachear evita gastar la cuota
        // mensual gratuita de decolecta.com en consultas repetidas del mismo DNI.
        // Solo se cachean respuestas exitosas — un fallo de red no debe quedar pegado.
        $cacheKey = "reniec_dni_{$dni}";
        $cached   = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(5)
                ->get('https://api.decolecta.com/v1/reniec/dni', ['numero' => $dni]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            $persona = [
                'nombre'   => $data['first_name'] ?? null,
                'apellido' => trim(($data['first_last_name'] ?? '') . ' ' . ($data['second_last_name'] ?? '')),
            ];

            Cache::forever($cacheKey, $persona);

            return $persona;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
