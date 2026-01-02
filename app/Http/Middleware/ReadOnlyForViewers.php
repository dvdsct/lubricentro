<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyForViewers
{
    /**
     * Handle an incoming request.
     * Blocks write operations for users with role 'viewer'.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('viewer')) {
            // Allow safe methods only
            if (!in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'])) {
                abort(403, 'Este usuario solo puede visualizar. Operación no permitida.');
            }
        }

        return $next($request);
    }
}
