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

            <!-- Geolocation Radar Status -->
            <div class="mb-6 flex flex-col items-center bg-slate-950/20 border border-white/5 rounded-xl p-6">
                <!-- Pulse animation container -->
                <div id="radar-container" class="relative w-16 h-16 flex items-center justify-center mb-3">
                    <div id="radar-pulse-1" class="absolute w-full h-full rounded-full bg-indigo-500/30 animate-ping"></div>
                    <div id="radar-pulse-2" class="absolute w-12 h-12 rounded-full bg-indigo-500/50 animate-ping"></div>
                    <div id="radar-icon" class="relative z-10 w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

                <div id="geo-status" class="text-sm font-semibold text-gray-300 text-center">
                    Obteniendo ubicación...
                </div>
                <p id="geo-desc" class="text-xs text-gray-500 text-center mt-1">
                    Por favor, permite el acceso al GPS para poder validar tu asistencia.
                </p>

                <!-- Hidden inputs to submit -->
                <form id="attendance-form" method="POST" action="{{ route('asistencia.store') }}" class="w-full mt-4">
                    @csrf
                    <input type="hidden" name="latitud" id="latitud">
                    <input type="hidden" name="longitud" id="longitud">

                    <button type="submit" id="btn-submit" disabled
                            class="w-full mt-2 py-3 px-4 rounded-xl font-bold tracking-wide transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900
                                   bg-gray-800 text-gray-500 border border-gray-700 cursor-not-allowed">
                        Esperando Ubicación...
                    </button>
                </form>
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

        // Geolocation
        const geoStatus = document.getElementById('geo-status');
        const geoDesc = document.getElementById('geo-desc');
        const radarIcon = document.getElementById('radar-icon');
        const radarPulse1 = document.getElementById('radar-pulse-1');
        const radarPulse2 = document.getElementById('radar-pulse-2');
        const btnSubmit = document.getElementById('btn-submit');
        const inputLat = document.getElementById('latitud');
        const inputLng = document.getElementById('longitud');

        function initGeoloc() {
            if (!navigator.geolocation) {
                markError('Soporte GPS no disponible', 'Tu navegador o dispositivo no soporta geolocalización.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    inputLat.value = lat;
                    inputLng.value = lng;

                    // Cambiar UI a éxito
                    radarPulse1.className = "absolute w-full h-full rounded-full bg-emerald-500/20";
                    radarPulse2.className = "absolute w-12 h-12 rounded-full bg-emerald-500/40";
                    radarIcon.className = "relative z-10 w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30";
                    radarIcon.innerHTML = `
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    `;

                    geoStatus.className = "text-sm font-semibold text-emerald-400 text-center";
                    geoStatus.textContent = "Ubicación detectada correctamente";
                    geoDesc.textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                    // Habilitar botón
                    btnSubmit.disabled = false;
                    btnSubmit.className = "w-full mt-2 py-3 px-4 rounded-xl font-bold tracking-wide transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 bg-gradient-to-r {{ $tipo === 'entrada' ? 'from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-emerald-600/20' : 'from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 shadow-rose-600/20' }} text-white shadow-lg cursor-pointer transform hover:-translate-y-0.5";
                    btnSubmit.textContent = "{{ $tipo === 'entrada' ? 'Confirmar Entrada' : 'Confirmar Salida' }}";
                },
                (error) => {
                    console.error(error);
                    let title = 'Error de ubicación';
                    let desc = 'No pudimos verificar tu ubicación física.';

                    if (error.code === error.PERMISSION_DENIED) {
                        title = 'Permiso GPS Denegado';
                        desc = 'Es obligatorio habilitar la ubicación en tu navegador para realizar el fichaje.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        title = 'GPS no disponible';
                        desc = 'No se ha podido determinar la ubicación de tu dispositivo.';
                    } else if (error.code === error.TIMEOUT) {
                        title = 'Tiempo de espera agotado';
                        desc = 'Se agotó el tiempo al intentar obtener la ubicación. Por favor, reintenta.';
                    }

                    markError(title, desc);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 12000,
                    maximumAge: 0
                }
            );
        }

        function markError(title, desc) {
            radarPulse1.className = "hidden";
            radarPulse2.className = "hidden";
            radarIcon.className = "relative z-10 w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/30";
            radarIcon.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            `;

            geoStatus.className = "text-sm font-semibold text-rose-400 text-center";
            geoStatus.textContent = title;
            geoDesc.textContent = desc;

            btnSubmit.disabled = true;
            btnSubmit.className = "w-full mt-2 py-3 px-4 rounded-xl font-bold tracking-wide transition duration-150 bg-rose-950/20 text-rose-500/50 border border-rose-950/40 cursor-not-allowed";
            btnSubmit.textContent = "Fichaje Bloqueado";
        }

        // Ejecutar inmediatamente
        window.addEventListener('load', initGeoloc);
    </script>
</x-guest-layout>
