<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el rol del usuario no es el que requiere la ruta, devolvemos un error 403.
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            abort(403, 'ACCESS DENIED');
        }

        return $next($request);
    }
}
