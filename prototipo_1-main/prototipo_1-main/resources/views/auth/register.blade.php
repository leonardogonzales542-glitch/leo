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
                <svg class="h-3 w-3 text-forest-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-forest-700">Nueva Cuenta</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">Registrarse</h1>
            <p class="mt-3 text-sm leading-relaxed text-slate-500">
                Crea tu cuenta en AGROERP para comenzar a gestionar tu negocio agrícola de manera eficiente.
            </p>
        </div>

        {{-- Register Form --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Name Input --}}
            <div class="animate-fade-in-up delay-100 group relative">
                <x-input-label for="name" value="Nombre Completo" />
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <x-text-input id="name" class="block w-full pl-11" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ej. Juan Pérez" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Email Input --}}
            <div class="animate-fade-in-up delay-200 group relative">
                <x-input-label for="email" value="Correo Electrónico" />
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <x-text-input id="email" class="block w-full pl-11" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="animate-fade-in-up delay-300 grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Telefono Input --}}
                <div class="group relative">
                    <x-input-label for="telefono" value="Teléfono" />
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <x-text-input id="telefono" class="block w-full pl-11" type="tel" name="telefono" :value="old('telefono')" autocomplete="tel" placeholder="Ej. 3001234567" />
                    </div>
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                {{-- Direccion Input --}}
                <div class="group relative">
                    <x-input-label for="direccion" value="Dirección" />
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <x-text-input id="direccion" class="block w-full pl-11" type="text" name="direccion" :value="old('direccion')" autocomplete="street-address" placeholder="Ej. Calle 123 # 4-5" />
                    </div>
                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                </div>
            </div>

            <div class="animate-fade-in-up delay-400 grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- Password Input --}}
                <div class="group relative">
                    <x-input-label for="password" value="Contraseña" />
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <x-text-input id="password" class="block w-full pl-11" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Confirm Password Input --}}
                <div class="group relative">
                    <x-input-label for="password_confirmation" value="Confirmar" />
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within:text-forest-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                        </div>
                        <x-text-input id="password_confirmation" class="block w-full pl-11" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="animate-fade-in-up delay-400 pt-3">
                <x-primary-button>
                    Crear Cuenta
                </x-primary-button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="animate-fade-in-up delay-500 mt-8 border-t border-slate-200 pt-6">
            <p class="text-center text-sm text-slate-500">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}" class="font-bold text-forest-700 transition-colors hover:text-forest-900">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
