<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica que el usuario autenticado sea Docente.
 * Si el usuario no es docente, redirige al dashboard del admin.
 */
class EsDocente
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario está autenticado y tiene rol de docente
        if (!$request->user() || !$request->user()->esDocente()) {
            return redirect()->route('admin.dashboard')
                             ->with('error', 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
