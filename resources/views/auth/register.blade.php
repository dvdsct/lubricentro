<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-0 bg-gradient-to-tr from-gray-900 via-slate-900 to-indigo-950 text-white">
        
        <div class="mb-4 text-center animate-fade-in-down">
            <h1 class="text-3xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 drop-shadow-md">
                ROCKET
            </h1>
            <p class="text-gray-400 mt-1 text-xs font-medium tracking-wide">
                Crear Nueva Cuenta de Empleado
            </p>
        </div>

        <div class="w-full sm:max-w-md mt-2 px-6 py-5 bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-xl relative">
            <!-- Decorative light blob -->
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="mb-4 text-center">
                <h2 class="text-lg font-bold text-gray-200">
                    Registro de Usuario
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Completa tus datos personales para acceder al sistema
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-3 p-2.5 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-xs font-medium">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nombre y Apellido</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           placeholder="Juan Pérez"
                           class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-white transition placeholder-gray-600 block mt-0.5" />
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           placeholder="ejemplo@rocket.com"
                           class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-white transition placeholder-gray-600 block mt-0.5" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-white transition placeholder-gray-600 block mt-0.5" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full bg-slate-950/50 border border-white/10 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-white transition placeholder-gray-600 block mt-0.5" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-2">
                        <label for="terms" class="flex items-center">
                            <input type="checkbox" name="terms" id="terms" required class="rounded bg-slate-950/50 border-white/10 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-slate-900" />
                            <span class="ms-2 text-xs text-gray-400">
                                {!! __('Acepto los :terms_of_service y :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-indigo-400 hover:text-indigo-300">'.__('Términos del Servicio').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-indigo-400 hover:text-indigo-300">'.__('Política de Privacidad').'</a>',
                                ]) !!}
                            </span>
                        </label>
                    </div>
                @endif

                <div class="pt-1">
                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-lg text-white font-bold tracking-wide shadow-lg shadow-indigo-600/20 transition duration-150 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                        Registrarse
                    </button>
                </div>

                <div class="text-center pt-1 mt-1">
                    <a class="underline text-xs text-gray-400 hover:text-white transition duration-150" href="{{ route('login') }}">
                        ¿Ya tienes una cuenta? Iniciar Sesión
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center mt-4 text-xs text-gray-500">
            &copy; {{ date('Y') }} Rocket Lubricentro. Todos los derechos reservados.
        </div>
    </div>

    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }
    </style>
</x-guest-layout>
