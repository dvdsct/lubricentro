<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-gray-900 via-slate-900 to-indigo-950 text-white">
        
        <div class="mb-8 text-center animate-fade-in-down">
            <h1 class="text-4xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 drop-shadow-md">
                ROCKET
            </h1>
            <p class="text-gray-400 mt-2 font-medium tracking-wide">
                Registro de Nuevo Usuario
            </p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl relative">
            <!-- Decorative light blob -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="mb-6 text-center">
                <h2 class="text-xl font-bold text-gray-200">
                    PIN de Registro Requerido
                </h2>
                <p class="text-xs text-gray-400 mt-1">
                    Introduce el PIN de acceso del lubricentro para registrar una nueva cuenta
                </p>
            </div>

            <!-- Session Status / Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm font-medium">
                    {{ $errors->first('pin') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify-pin') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="pin" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">PIN de Registro</label>
                    <input id="pin" type="password" name="pin" required autocomplete="off" autofocus
                           placeholder="•••••••"
                           class="w-full text-center text-2xl font-mono tracking-widest bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-white transition placeholder-gray-600" />
                </div>

                <div>
                    <button type="submit"
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl text-white font-bold tracking-wide shadow-lg shadow-indigo-600/20 transition duration-150 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Validar PIN y Registrarse
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-8 text-xs text-gray-500">
            &copy; {{ date('Y') }} Rocket Lubricentro. Todos los derechos reservados.
        </div>
    </div>

    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.6s ease-out;
        }
    </style>
</x-guest-layout>
