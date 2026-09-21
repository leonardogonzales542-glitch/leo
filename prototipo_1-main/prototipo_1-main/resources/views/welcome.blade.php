<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AGROERP — Sistema de inventario y ventas.">
    <title>{{ config('app.name', 'AGROERP') }} — Inventario y Ventas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased">

    {{-- ══ Background Decorative Layer ══ --}}
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_10%,rgba(56,189,248,0.14),transparent_50%),radial-gradient(ellipse_at_20%_90%,rgba(2,132,199,0.12),transparent_50%),linear-gradient(160deg,#020617_0%,#0f172a_40%,#1e293b_100%)]"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><rect width=%2260%22 height=%2260%22 fill=%22none%22 stroke=%22white%22 stroke-width=%220.5%22/></svg>');"></div>
        {{-- Floating orbs --}}
        <div class="absolute right-[15%] top-[8%] h-72 w-72 animate-pulse-glow rounded-full bg-agro-lime/10 blur-[100px]"></div>
        <div class="absolute bottom-[15%] left-[10%] h-56 w-56 animate-pulse-glow rounded-full bg-forest-600/20 blur-[80px] delay-500"></div>
        <div class="absolute right-[40%] top-[60%] h-40 w-40 animate-float-slow rounded-full bg-agro-amber/8 blur-[60px]"></div>
    </div>

    {{-- ══ Main Content ══ --}}
    <div class="relative z-10 min-h-screen">

        {{-- ── Navbar ── --}}
        <header class="animate-fade-in">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                {{-- Logo --}}
                <a class="group flex items-center gap-3" href="/">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur-sm transition-all duration-300 group-hover:bg-white/15 group-hover:ring-white/30">
                        <svg class="h-6 w-6 text-agro-lime transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M12 3C7 8 4 11.8 4 14.5A8 8 0 0 0 12 22a8 8 0 0 0 8-7.5C20 11.8 17 8 12 3z" />
                            <path d="M9 15c1.5-2.7 3.5-2.7 5 0" />
                        </svg>
                    </span>
                    <span class="text-lg font-extrabold tracking-wide text-white">AGROERP</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden items-center gap-8 lg:flex">
                    <a class="text-sm font-medium text-white/70 transition-colors duration-200 hover:text-agro-lime" href="#">Inicio</a>
                    <a class="text-sm font-medium text-white/70 transition-colors duration-200 hover:text-agro-lime" href="#nosotros">Nosotros</a>
                    <a class="text-sm font-medium text-white/70 transition-colors duration-200 hover:text-agro-lime" href="#productos">Productos</a>
                    <a class="text-sm font-medium text-white/70 transition-colors duration-200 hover:text-agro-lime" href="#contacto">Contacto</a>
                </div>

                {{-- CTA --}}
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-agro-lime px-6 py-2.5 text-sm font-bold tracking-wide text-forest-950 shadow-lg shadow-agro-lime/20 transition-all duration-300 hover:shadow-xl hover:shadow-agro-lime/30 hover:-translate-y-0.5">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 backdrop-blur-sm transition-all duration-300 hover:bg-white/15 hover:ring-white/30">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hidden items-center gap-2 rounded-xl bg-agro-lime px-5 py-2.5 text-sm font-bold tracking-wide text-forest-950 shadow-lg shadow-agro-lime/20 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 sm:inline-flex">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        {{-- ── Hero Section ── --}}
        <main>
            <section class="mx-auto grid min-h-[calc(100vh-96px)] max-w-7xl items-center gap-12 px-6 py-12 lg:grid-cols-[1fr_0.85fr] lg:px-8">

                {{-- Left Column — Content --}}
                <div class="max-w-2xl">
                    {{-- Badge --}}
                    <div class="animate-fade-in-up mb-8 inline-flex items-center gap-2.5 rounded-full border border-agro-lime/20 bg-white/5 px-5 py-2 backdrop-blur-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-agro-lime opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-agro-lime"></span>
                        </span>
                        <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-agro-lime">Inventario inteligente de insumos agrícolas</span>
                    </div>

                    {{-- Headline --}}
                    <h1 class="animate-slide-up text-5xl font-extrabold leading-[1.05] tracking-tight text-white md:text-6xl lg:text-7xl">
                        AGRO<span class="mt-2 block bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text text-transparent">ERP</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="animate-fade-in-up delay-200 mt-6 max-w-xl text-lg leading-relaxed text-white/70">
                        Gestiona inventario, ventas, compras y trazabilidad de insumos agrícolas con una plataforma moderna, eficiente y diseñada para el campo colombiano.
                    </p>

                    {{-- CTAs --}}
                    <div class="animate-fade-in-up delay-300 mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-agro-amber to-agro-amber-light px-8 py-4 text-sm font-bold uppercase tracking-[0.12em] text-white shadow-xl shadow-agro-amber/25 transition-all duration-300 hover:shadow-2xl hover:shadow-agro-amber/35 hover:-translate-y-0.5">
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            Acceso al Sistema
                        </a>
                        <a href="#nosotros" class="inline-flex items-center gap-2.5 rounded-xl border border-white/20 bg-white/5 px-8 py-4 text-sm font-bold uppercase tracking-[0.12em] text-white backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:border-white/30">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 16 12 12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                            Conocer Más
                        </a>
                    </div>

                    {{-- Trust Metrics --}}
                    <div class="animate-fade-in-up delay-500 mt-10 flex flex-wrap items-center gap-x-8 gap-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-agro-lime/15">
                                <svg class="h-4 w-4 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-white/60">95% rendimiento</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-agro-lime/15">
                                <svg class="h-4 w-4 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-white/60">+200 clientes</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-agro-lime/15">
                                <svg class="h-4 w-4 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                            </div>
                            <span class="text-sm font-semibold text-white/60">Lotes trazables</span>
                        </div>
                    </div>
                </div>

                {{-- Right Column — Dashboard Preview Card --}}
                <div class="animate-scale-in delay-300 relative hidden lg:flex lg:justify-end">
                    {{-- Decorative elements --}}
                    <div class="absolute -right-6 -top-6 h-64 w-64 animate-pulse-glow rounded-full bg-agro-lime/15 blur-[80px]"></div>
                    <div class="absolute -bottom-10 -left-10 h-40 w-40 animate-float rounded-full bg-agro-amber/10 blur-[60px]"></div>

                    {{-- Main Card --}}
                    <div class="relative w-full max-w-md rounded-2xl border border-white/10 bg-white/5 p-7 shadow-2xl backdrop-blur-md">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-agro-lime/15">
                                    <svg class="h-5 w-5 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 19h16"/><path d="M7 19V9"/><path d="M12 19V4"/><path d="M17 19V13"/>
                                    </svg>
                                </span>
                                <div>
                                    <span class="text-sm font-bold text-white">Panel Operativo</span>
                                    <p class="text-xs text-white/40">Tiempo real</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-agro-lime/30 bg-agro-lime/10 px-3 py-1">
                                <span class="h-1.5 w-1.5 rounded-full bg-agro-lime animate-pulse"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-agro-lime">Online</span>
                            </span>
                        </div>

                        {{-- Stats Grid --}}
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="group rounded-xl border border-white/8 bg-white/5 p-4 transition-all duration-300 hover:border-agro-lime/20 hover:bg-white/8">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-white/40">Productos</div>
                                <div class="mt-2 text-3xl font-extrabold text-white">847</div>
                                <div class="mt-1 flex items-center gap-1">
                                    <svg class="h-3 w-3 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                    <span class="text-[10px] font-semibold text-agro-lime">+12%</span>
                                </div>
                            </div>
                            <div class="group rounded-xl border border-white/8 bg-white/5 p-4 transition-all duration-300 hover:border-agro-lime/20 hover:bg-white/8">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-white/40">Ventas Hoy</div>
                                <div class="mt-2 text-3xl font-extrabold text-white">24</div>
                                <div class="mt-1 flex items-center gap-1">
                                    <svg class="h-3 w-3 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                    <span class="text-[10px] font-semibold text-agro-lime">+8%</span>
                                </div>
                            </div>
                            <div class="group rounded-xl border border-white/8 bg-white/5 p-4 transition-all duration-300 hover:border-agro-lime/20 hover:bg-white/8">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-white/40">Clientes</div>
                                <div class="mt-2 text-3xl font-extrabold text-white">215</div>
                                <div class="mt-1 flex items-center gap-1">
                                    <svg class="h-3 w-3 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                    <span class="text-[10px] font-semibold text-agro-lime">+5%</span>
                                </div>
                            </div>
                            <div class="group rounded-xl border border-white/8 bg-white/5 p-4 transition-all duration-300 hover:border-agro-lime/20 hover:bg-white/8">
                                <div class="text-[10px] font-bold uppercase tracking-widest text-white/40">Ingresos</div>
                                <div class="mt-2 text-2xl font-extrabold text-white">$4.2M</div>
                                <div class="mt-1 flex items-center gap-1">
                                    <svg class="h-3 w-3 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                                    <span class="text-[10px] font-semibold text-agro-lime">+18%</span>
                                </div>
                            </div>
                        </div>

                        {{-- Inventory Progress --}}
                        <div class="mt-5 rounded-xl border border-white/8 bg-white/5 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-white/40">Inventario General</span>
                                <span class="text-xs font-bold text-agro-lime">78%</span>
                            </div>
                            <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-white/10">
                                <div class="h-full w-[78%] rounded-full bg-gradient-to-r from-forest-600 to-agro-lime transition-all duration-1000"></div>
                            </div>
                        </div>

                        {{-- Recent Activity --}}
                        <div class="mt-4 space-y-2.5">
                            <div class="flex items-center gap-3 rounded-lg border border-white/5 bg-white/[0.03] px-3 py-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-agro-lime/10">
                                    <svg class="h-4 w-4 text-agro-lime" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-white/80">Venta de fertilizantes</p>
                                    <p class="text-[10px] text-white/30">Hace 5 min</p>
                                </div>
                                <span class="text-xs font-bold text-agro-lime">+$125K</span>
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border border-white/5 bg-white/[0.03] px-3 py-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-agro-amber/10">
                                    <svg class="h-4 w-4 text-agro-amber" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-white/80">Entrada de semillas</p>
                                    <p class="text-[10px] text-white/30">Hace 12 min</p>
                                </div>
                                <span class="text-xs font-bold text-agro-amber-light">+340 u.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- ── Footer ── --}}
        <footer class="border-t border-white/5">
            <div class="mx-auto max-w-7xl px-6 py-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-xs text-white/30">&copy; {{ date('Y') }} AGROERP. Todos los derechos reservados.</p>
                    <p class="text-xs text-white/20">Inventario y ventas de insumos agrícolas</p>
                </div>
            </div>
        </footer>
    </div>

</body>
</html>
