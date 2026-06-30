<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-gray-900 via-slate-900 to-indigo-950 text-white">
        
        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl relative text-center">
            <!-- Decorative light blob -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Success Checkmark Animation -->
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center mx-auto mb-6 text-emerald-400 shadow-lg shadow-emerald-500/20 animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-white">
                ¡Fichaje Exitoso!
            </h2>
            <p class="text-gray-400 mt-2 text-sm">
                Se ha registrado tu marca en el sistema correctamente.
            </p>

            <!-- Detalles del Fichaje -->
            <div class="mt-6 bg-slate-950/40 border border-white/5 rounded-xl p-5 text-left space-y-3 font-medium">
                <div class="flex justify-between items-center border-b border-white/5 pb-2">
                    <span class="text-gray-500 text-xs uppercase tracking-wider">Empleado:</span>
                    <span class="text-white text-sm font-semibold">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-white/5 pb-2">
                    <span class="text-gray-500 text-xs uppercase tracking-wider">Registro:</span>
                    @if ($asistencia->tipo === 'entrada')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 uppercase tracking-wide">
                            Entrada
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 border border-rose-500/30 text-rose-400 uppercase tracking-wide">
                            Salida
                        </span>
                    @endif
                </div>
                <div class="flex justify-between items-center border-b border-white/5 pb-2">
                    <span class="text-gray-500 text-xs uppercase tracking-wider">Fecha y Hora:</span>
                    <span class="text-white text-sm font-mono">{{ $asistencia->fecha_hora->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-xs uppercase tracking-wider">Ubicación GPS:</span>
                    <span class="text-white text-xs font-mono">{{ number_format($asistencia->latitud, 6) }}, {{ number_format($asistencia->longitud, 6) }}</span>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-8 space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="w-full block py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-xl text-white font-bold tracking-wide transition duration-150">
                    Ir al Dashboard principal
                </a>

                <!-- Cerrar Sesión para el siguiente empleado -->
                <form method="POST" action="{{ route('asistencia.logout') }}" class="w-full">
                    @csrf
                    <!-- Redirección después de desloguear -->
                    <input type="hidden" name="redirect_to" value="{{ route('asistencia.scan') }}">
                    <button type="submit"
                            class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 rounded-xl text-white font-bold tracking-wide shadow-lg shadow-indigo-600/20 transition duration-150">
                        Cerrar Sesión (Siguiente Empleado)
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-8 text-xs text-gray-500">
            &copy; {{ date('Y') }} Rocket Lubricentro. Todos los derechos reservados.
        </div>
    </div>
</x-guest-layout>
