<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictEmpleados
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasAnyRole(['mecánico', 'lavadero', 'maestranza', 'empleado'])) {
            // Define allowed routes for these roles
            $allowedRoutes = [
                'asistencia.mi-historial',
                'asistencia.registrar',
                'asistencia.store',
                'asistencia.logout',
                'logout',
            ];

            $routeName = $request->route() ? $request->route()->getName() : null;
            $path = $request->path();

            // Allow if route name is in allowed list, or if it is a livewire route, or is JSON/ajax
            if (in_array($routeName, $allowedRoutes) || 
                str_starts_with($path, 'livewire/') || 
                $request->expectsJson() ||
                $request->ajax()) {
                return $next($request);
            }

            // Redirect to personal history
            return redirect()->route('asistencia.mi-historial');
        }

        return $next($request);
    }
}
