<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica que el usuario autenticado sea Administrador.
 * Si el usuario no es admin, redirige al dashboard correspondiente.
 */
class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario está autenticado y tiene rol de admin
        if (!$request->user() || !$request->user()->esAdmin()) {
            return redirect()->route('docente.dashboard')
                             ->with('error', 'No tienes permisos para acceder a esa sección.');
        }

        return $next($request);
    }
}
