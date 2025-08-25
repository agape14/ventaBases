<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibrosAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Roles que pueden acceder al sistema de libros
        $allowedRoles = ['admin', 'tesoreria', 'ventas', 'libros'];
        
        // Verificar si el usuario tiene un rol permitido
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'No tienes permisos para acceder al Sistema de Libros.');
        }

        return $next($request);
    }
}
