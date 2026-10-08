<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-gray-900 via-slate-900 to-indigo-950 text-white">
        
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 drop-shadow-md">
                ROCKET
            </h1>
            <p class="text-gray-400 mt-2 font-medium tracking-wide">
                Fichaje de Asistencia
            </p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl relative">
            <!-- Decorative light blob -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="text-center mb-6">
                <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold block mb-1">Sesión Iniciada</span>
                <h2 class="text-2xl font-extrabold text-white">
                    ¡Hola, {{ $user->name }}!
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $user->email }}
                </p>
            </div>

            <!-- Movimiento Badge -->
            <div class="flex justify-center mb-6">
                @if ($tipo === 'entrada')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wider animate-pulse">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>
                        Corresponde: Entrada
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-500/10 border border-rose-500/30 text-rose-400 uppercase tracking-wider animate-pulse">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mr-2"></span>
                        Corresponde: Salida
                    </span>
                @endif
            </div>

            <!-- Reloj y Fecha -->
            <div class="bg-slate-950/40 border border-white/5 rounded-xl p-4 text-center mb-6">
                <div id="reloj" class="text-3xl font-mono font-bold text-transparent bg-clip-text bg-gradient-to-r from-gray-100 to-gray-300">
                    --:--:--
                </div>
                <div id="fecha" class="text-xs text-gray-500 uppercase tracking-wider mt-1">
                    Cargando fecha...
                </div>
            </div>

            <!-- Formulario de Fichaje -->
            <div class="mb-6 flex flex-col items-center bg-slate-950/20 border border-white/5 rounded-xl p-6">
                <form id="attendance-form" method="POST" action="{{ route('asistencia.store') }}" class="w-full">
                    @csrf
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">

                    <button type="submit" id="btn-submit"
                            class="w-full py-4 px-4 rounded-xl font-bold tracking-wide transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 bg-gradient-to-r {{ $tipo === 'entrada' ? 'from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-emerald-600/20' : 'from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 shadow-rose-600/20' }} text-white shadow-lg cursor-pointer transform hover:-translate-y-0.5 text-lg">
                        {{ $tipo === 'entrada' ? 'Confirmar Entrada' : 'Confirmar Salida' }}
                    </button>
                </form>

                <div id="geo-status" class="text-xs text-gray-500 text-center mt-3 flex items-center justify-center">
                    <span id="geo-icon" class="mr-1"></span>
                    <span id="geo-text"></span>
                </div>
            </div>

            <!-- Botón de Cerrar Sesión si no es el usuario correspondiente -->
            <div class="text-center mt-4">
                <form method="POST" action="{{ route('asistencia.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium underline">
                        ¿No eres tú? Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-8 text-xs text-gray-500">
            &copy; {{ date('Y') }} Rocket Lubricentro. Todos los derechos reservados.
        </div>
    </div>

    <!-- Script de Geolocation y Reloj -->
    <script>
        // Reloj en vivo
        function actualizarReloj() {
            const ahora = new Date();
            const horas = String(ahora.getHours()).padStart(2, '0');
            const minutos = String(ahora.getMinutes()).padStart(2, '0');
            const segundos = String(ahora.getSeconds()).padStart(2, '0');
            document.getElementById('reloj').textContent = `${horas}:${minutos}:${segundos}`;

            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('fecha').textContent = ahora.toLocaleDateString('es-ES', opcionesFecha);
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        // Intento no bloqueante de geolocalización
        const inputLat = document.getElementById('latitud');
        const inputLng = document.getElementById('longitud');
        const geoText = document.getElementById('geo-text');
        const geoIcon = document.getElementById('geo-icon');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    inputLat.value = position.coords.latitude;
                    inputLng.value = position.coords.longitude;
                    if (geoText && geoIcon) {
                        geoIcon.textContent = '📍';
                        geoText.textContent = 'Ubicación detectada';
                        geoText.className = 'text-xs text-emerald-400';
                    }
                },
                (error) => {
                    // Silencioso: la ubicación ya no es obligatoria
                    console.log('Geolocalización opcional no disponible:', error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 8000,
                    maximumAge: 60000
                }
            );
        }
    </script>
</x-guest-layout>
