<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlySpectator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('spectator')) {
            // Optional: reject file uploads outright for spectators
            if ($request->hasFile(null) || count($request->allFiles()) > 0) {
                abort(403, 'Este usuario es solo de lectura (espectador). No puede subir archivos.');
            }

            // Run the entire request in a transaction and always roll it back
            // This keeps Livewire (POST-based) browsing functional while ensuring
            // no persistent changes are committed to the database.
            \DB::beginTransaction();
            try {
                $response = $next($request);
                // Discard any DB changes
                \DB::rollBack();
                return $response;
            } catch (\Throwable $e) {
                // Ensure rollback on errors as well
                if (\DB::transactionLevel() > 0) {
                    \DB::rollBack();
                }
                throw $e;
            }
        }

        return $next($request);
    }
}
