<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Solo forzar HTTPS en producción
        if (App::environment('production') || App::environment('staging')) {
            if (!$request->secure() && !$request->is('health')) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
