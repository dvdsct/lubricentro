<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictCliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('cliente')) {
            $allowedRoutes = [
                'portal.cliente',
                'pdf.presupuesto',
                'logout',
            ];

            $routeName = $request->route() ? $request->route()->getName() : null;
            $path = $request->path();

            if (in_array($routeName, $allowedRoutes) || 
                str_starts_with($path, 'livewire/') || 
                $request->expectsJson() || 
                $request->ajax()) {
                return $next($request);
            }

            return redirect()->route('portal.cliente');
        }

        return $next($request);
    }
}
