<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Controlador de autenticación.
 * Maneja el login y logout del sistema.
 * No hay registro público: solo el admin puede crear docentes.
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        // Si ya está autenticado, redirigir según su rol
        if (Auth::check()) {
            return $this->redirigirSegunRol();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Intentar autenticar al usuario con las credenciales
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Verificar si el docente está activo
            // Los administradores siempre pueden iniciar sesión
            if ($user->esDocente() && !$user->activo) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
                ]);
            }

            // Regenerar la sesión para prevenir session fixation
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            return $this->redirigirSegunRol();
        }

        // Si las credenciales son incorrectas
        throw ValidationException::withMessages([
            'username' => 'Las credenciales proporcionadas no son correctas.',
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirige al usuario según su rol.
     */
    private function redirigirSegunRol()
    {
        if (Auth::user()->esAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('docente.dashboard');
    }
}
