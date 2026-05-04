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
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para la gestión de docentes desde el panel administrador.
 * Permite crear, editar, ver detalle, y activar/desactivar docentes.
 */
class DocenteController extends Controller
{
    /**
     * Lista todos los docentes de forma paginada.
     */
    public function index()
    {
        $docentes = User::where('rol', 'docente')
                        ->withCount('asignaciones')  // Número de asignaciones por docente
                        ->withCount('archivos')       // Número de archivos subidos
                        ->paginate(15);

        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Muestra el formulario para crear un nuevo docente.
     */
    public function create()
    {
        // Cargar datos necesarios para el formulario de asignaciones
        $cursos   = Curso::orderBy('nombre')->get();
        $grados   = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $secciones = Seccion::orderBy('nombre')->get();

        return view('admin.docentes.create', compact('cursos', 'grados', 'secciones'));
    }

    /**
     * Crea un nuevo docente con sus asignaciones.
     * Las asignaciones se reciben como un array dinámico desde el formulario.
     */
    public function store(StoreDocenteRequest $request)
    {
        // Crear el usuario docente
        $docente = User::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rol'      => 'docente',
            'activo'   => true,
        ]);

        // Crear las asignaciones dinámicas si se proporcionaron
        if ($request->filled('asignaciones')) {
            foreach ($request->asignaciones as $asignacion) {
                // Usar firstOrCreate para evitar duplicados
                DocenteAsignacion::firstOrCreate([
                    'user_id'    => $docente->id,
                    'curso_id'   => $asignacion['curso_id'],
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
        $cursos    = Curso::orderBy('nombre')->get();
        $grados    = Grado::with('nivel')->orderBy('nivel_id')->orderBy('nombre')->get();
        $secciones = Seccion::orderBy('nombre')->get();

        // Cargar las asignaciones actuales del docente
        $docente->load(['asignaciones.curso', 'asignaciones.grado', 'asignaciones.seccion']);

        return view('admin.docentes.edit', compact('docente', 'cursos', 'grados', 'secciones'));
    }

    /**
     * Actualiza los datos del docente y sincroniza sus asignaciones.
     */
    public function update(UpdateDocenteRequest $request, User $docente)
    {
        // Preparar los datos a actualizar
        $datos = [
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
        ];

        // Solo actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $docente->update($datos);

        // Sincronizar asignaciones: eliminar las existentes y crear las nuevas
        // Esta estrategia es más segura que intentar actualizar individualmente
        $docente->asignaciones()->delete();

        if ($request->filled('asignaciones')) {
            foreach ($request->asignaciones as $asignacion) {
                DocenteAsignacion::create([
                    'user_id'    => $docente->id,
                    'curso_id'   => $asignacion['curso_id'],
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
}
