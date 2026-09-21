<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AGROERP') }} — Acceso</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-forest-950 font-sans text-slate-100 antialiased selection:bg-agro-lime selection:text-forest-950">
        <div class="relative flex min-h-screen">
            
            {{-- ══ Background Decor Layer ══ --}}
            <div class="fixed inset-0 z-0 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(184,222,137,0.1),transparent_50%),radial-gradient(ellipse_at_bottom_left,rgba(39,109,66,0.15),transparent_50%)]"></div>
            </div>

            {{-- ══ Left Panel: Brand Showcase (Hidden on Mobile) ══ --}}
            <div class="relative z-10 hidden w-0 flex-1 flex-col justify-between overflow-hidden bg-forest-900/50 p-12 lg:flex lg:w-1/2">
                {{-- Decorative elements --}}
                <div class="absolute -left-20 -top-20 h-64 w-64 animate-pulse-glow rounded-full bg-agro-lime/10 blur-[80px]"></div>
                <div class="absolute bottom-10 right-10 h-40 w-40 animate-float rounded-full bg-forest-600/20 blur-[60px]"></div>

                {{-- Top: Logo --}}
                <div class="animate-fade-in relative z-20">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur-sm transition-all duration-300 group-hover:bg-white/15 group-hover:scale-105">
                            <svg class="h-6 w-6 text-agro-lime" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M12 3C7 8 4 11.8 4 14.5A8 8 0 0 0 12 22a8 8 0 0 0 8-7.5C20 11.8 17 8 12 3z" />
                                <path d="M9 15c1.5-2.7 3.5-2.7 5 0" />
                            </svg>
                        </span>
                        <span class="text-xl font-extrabold tracking-wide text-white">AGROERP</span>
                    </a>
                </div>

                {{-- Middle: Value Prop --}}
                <div class="animate-slide-up relative z-20 mt-12 max-w-lg">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-agro-lime/20 bg-white/5 px-4 py-1.5 backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-agro-lime"></span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-agro-lime">Plataforma Agrícola</span>
                    </div>
                    <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-white">
                        Gestión total para tu agro-negocio
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-white/60">
                        Administra el inventario de insumos agrícolas, controla las ventas en tiempo real y mantén la trazabilidad de tus productos con nuestra plataforma diseñada para el campo.
                    </p>

                    {{-- Mini Stats --}}
                    <div class="mt-8 grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                            <div class="flex items-center gap-2 text-white/50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Stock Total</span>
                            </div>
                            <div class="mt-2 text-2xl font-black text-white">24.5k</div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                            <div class="flex items-center gap-2 text-white/50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Usuarios Activos</span>
                            </div>
                            <div class="mt-2 text-2xl font-black text-white">+1,200</div>
                        </div>
                    </div>
                </div>

                {{-- Bottom: Copyright --}}
                <div class="animate-fade-in relative z-20 text-xs font-medium text-white/40">
                    &copy; {{ date('Y') }} AGROERP. Yaguará, Huila.
                </div>
            </div>

            {{-- ══ Right Panel: Form Area ══ --}}
            <div class="relative z-10 flex w-full flex-1 flex-col justify-center bg-[#f8fdf4] px-6 py-12 lg:w-1/2 lg:flex-none lg:px-20 xl:px-24">
                {{-- Decorative subtle background --}}
                <div class="absolute inset-0 z-0 bg-[radial-gradient(circle_at_center,rgba(184,222,137,0.05),transparent_80%)]"></div>
                
                {{-- Form Container --}}
                <div class="relative z-10 mx-auto w-full max-w-sm lg:max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
