<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    private string $configPath = 'config/institucion.json';

    /** Muestra la página de configuración */
    public function index()
    {
        $institucion = $this->getInstitucion();
        return view('admin.configuracion', compact('institucion'));
    }

    /** Actualiza el perfil del administrador */
    public function updatePerfil(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'username' => 'required|string|max:60|unique:users,username,' . Auth::id(),
        ]);

        Auth::user()->update($request->only('nombre', 'apellido', 'username'));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    /** Cambia la contraseña del administrador */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password'        => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->password_actual, Auth::user()->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])
                         ->with('tab', 'seguridad');
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /** Actualiza la información de la institución */
    public function updateInstitucion(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:150',
            'sitio_web' => 'nullable|url|max:150',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $datos = $request->only('nombre', 'direccion', 'telefono', 'email', 'sitio_web');
        $institucionActual = $this->getInstitucion();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $datos['logo'] = 'images/' . $filename;
        } else {
            $datos['logo'] = $institucionActual['logo'] ?? 'images/logo_iep.jpg';
        }

        Storage::put($this->configPath, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return back()->with('success', 'Información de la institución actualizada.');
    }

    /** Lee la configuración de la institución desde el archivo JSON */
    private function getInstitucion(): array
    {
        if (Storage::exists($this->configPath)) {
            return json_decode(Storage::get($this->configPath), true) ?? [];
        }
        return [
            'nombre'    => 'I.E.P Esther Carson',
            'direccion' => '',
            'telefono'  => '',
            'email'     => '',
            'sitio_web' => '',
        ];
    }
}
