
<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Agrícola / Cliente - Insumos Agrícolas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://kit.fontawesome.com/dcb1bbced2.js" crossorigin="anonymous"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        agro: {
                            50: '#f2fbf5',
                            100: '#e1f6e8',
                            200: '#c4ebd4',
                            300: '#96d8b6',
                            400: '#60bc90',
                            500: '#3ba376',
                            600: '#2b825d',
                            700: '#25684d',
                            800: '#20533f',
                            900: '#1b4435',
                            950: '#0e261d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('css')
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full lg:w-72 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between border-r border-slate-800">
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 flex items-center justify-between bg-slate-950/60 border-b border-slate-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-agro-600 to-agro-400 flex items-center justify-center text-white shadow-lg shadow-agro-900/40">
                            <i class="fas fa-tractor text-lg"></i>
                        </div>
                        <div>
                            <span class="font-heading font-extrabold text-xl text-white tracking-tight">AGRO<span class="text-agro-400">CLIENTE</span></span>
                            <span class="block text-[10px] text-agro-400 font-semibold tracking-widest uppercase">Portal de Compras</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Nav Menu -->
                <div class="px-4 py-6 space-y-6" :class="{ 'block': sidebarOpen, 'hidden lg:block': !sidebarOpen }">
                    <div>
                        <a href="" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-agro-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i class="fas fa-home text-lg w-5 text-center"></i>
                            <span>Inicio / Portal</span>
                        </a>
                    </div>

                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Compras & Catálogo</p>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('cliente.catalogo') ? 'bg-slate-800 text-agro-400 border-l-4 border-agro-500' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                            <i class="fas fa-store text-amber-400 w-5 text-center"></i>
                            <span>Catálogo de Insumos</span>
                        </a>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('cliente.mis_compras') ? 'bg-slate-800 text-agro-400 border-l-4 border-agro-500' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                            <i class="fas fa-history text-blue-400 w-5 text-center"></i>
                            <span>Mis Compras & Facturas</span>
                        </a>
                    </div>

                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Mi Cuenta</p>

                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('profile.edit') ? 'bg-slate-800 text-agro-400 border-l-4 border-agro-500' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                            <i class="fas fa-user-cog w-5 text-center"></i>
                            <span>Editar Perfil</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Badge -->
            <div class="p-4 bg-slate-950/80 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-agro-600 text-white flex items-center justify-center font-bold font-heading">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-agro-400 font-medium">Cliente Registrado</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-30">
                <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-agro-50 text-agro-700 border border-agro-200">
                    <span class="w-2 h-2 rounded-full bg-agro-500 animate-pulse"></span>
                    Portal Agrícola Activo
                </span>

                <div class="flex items-center gap-4" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors">
                        <span class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-6 top-16 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fas fa-user-cog text-slate-400"></i> Mi Perfil
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 lg:p-10 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('js')
</body>
</html>
