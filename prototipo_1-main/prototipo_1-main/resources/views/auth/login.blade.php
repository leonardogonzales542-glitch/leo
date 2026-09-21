<x-guest-layout>
    <div class="animate-fade-in-up w-full">
        {{-- Header Section --}}
        <div class="mb-8">
            {{-- Mobile Logo (visible only on small screens) --}}
            <div class="mb-8 flex lg:hidden">
                <a href="/" class="flex items-center gap-3 group">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-forest-950 text-agro-lime shadow-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M12 3C7 8 4 11.8 4 14.5A8 8 0 0 0 12 22a8 8 0 0 0 8-7.5C20 11.8 17 8 12 3z" />
                            <path d="M9 15c1.5-2.7 3.5-2.7 5 0" />
                        </svg>
                    </span>
                    <span class="text-lg font-extrabold tracking-wide text-forest-950">AGROERP</span>
                </a>
            </div>

            <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-forest-200 bg-forest-50 px-3 py-1">
                <svg class="h-3 w-3 text-forest-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-forest-700">Acceso Seguro</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Iniciar Sesión</h1>
            <p class="mt-3 text-sm leading-relaxed text-slate-500">
                Bienvenido de vuelta. Ingresa tus credenciales para gestionar el inventario y las ventas de insumos.
            </p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email Input --}}
            <div class="animate-fade-in-up delay-100 group relative">
                <x-input-label for="email" value="Correo Electrónico" />
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <x-text-input id="email" class="block w-full pl-11" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@correo.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password Input --}}
            <div class="animate-fade-in-up delay-200 group relative">
                <div class="flex items-center justify-between">
                    <x-input-label for="password" value="Contraseña" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-forest-600 transition-colors hover:text-forest-800" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <x-text-input id="password" class="block w-full pl-11" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me --}}
            <div class="animate-fade-in-up delay-300 flex items-center">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                    <div class="relative flex items-center">
                        <input id="remember_me" type="checkbox" class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-slate-300 bg-white transition-all checked:border-forest-600 checked:bg-forest-600 focus:outline-none focus:ring-2 focus:ring-forest-600/20" name="remember">
                        <svg class="pointer-events-none absolute left-1/2 top-1/2 h-3 w-3 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-600">Recordar sesión</span>
                </label>
            </div>

            {{-- Submit Button --}}
            <div class="animate-fade-in-up delay-400 pt-2">
                <x-primary-button>
                    Ingresar al Panel
                </x-primary-button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="animate-fade-in-up delay-500 mt-8 border-t border-slate-200 pt-6">
            <p class="text-center text-sm text-slate-500">
                ¿Aún no tienes cuenta?
                <a href="{{ route('register') }}" class="font-bold text-forest-700 transition-colors hover:text-forest-900">
                    Registrarse ahora
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
