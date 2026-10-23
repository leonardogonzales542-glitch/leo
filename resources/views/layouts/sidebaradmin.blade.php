@php
$user = auth()->user();
$id_rol = $user->id_rol ?? 0;
$rol_nombre = '';
switch ($id_rol) {
    case 1:
        $rol_nombre = 'Admin Principal';
        break;
    case 2:
        $rol_nombre = 'Vendedor';
        break;
    case 3:
        $rol_nombre = 'Cliente';
        break;
}
$nombreCompleto = $user->usuario ?? 'Usuario';
$currentRoute = request()->route()?->getName() ?? '';
@endphp

<style>
.sidebar-link {
    color: #6b7280;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 10px 16px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.sidebar-link i {
    width: 24px;
    text-align: center;
    color: #9ca3af;
    font-size: 1.1rem;
}
.sidebar-link:hover {
    background-color: #f3f4f6;
    color: #111827;
}
.sidebar-link:hover i {
    color: #4b5563;
}
.sidebar-link.active-link {
    color: #111827;
    font-weight: 700;
}
.sidebar-link.active-link i {
    color: #111827;
}
/* Orange theme for branding */
.brand-icon {
    color: #f97316;
}
.brand-subtitle {
    color: #f97316;
    letter-spacing: 0.5px;
}

/* Responsive Sidebar Behavior */
.sidebar-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(3px);
    z-index: 1055;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.sidebar-backdrop.show {
    display: block;
    opacity: 1;
}
@media (min-width: 992px) {
    .admin-sidebar {
        width: 250px;
        min-width: 250px;
        height: 100vh;
        position: sticky;
        top: 0;
    }
    .sidebar-backdrop {
        display: none !important;
    }
}
@media (max-width: 991.98px) {
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 280px;
        max-width: 85vw;
        height: 100vh;
        z-index: 1060;
        background-color: var(--bs-body-bg) !important;
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.4);
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .admin-sidebar.show-sidebar {
        transform: translateX(0);
    }
}
</style>

<aside id="adminSidebar" class="admin-sidebar flex-shrink-0 bg-body-tertiary border-end d-flex flex-column">
    <!-- Brand Logo Section -->
    <div class="d-flex align-items-center justify-content-between bg-white px-3 py-3 border-bottom" style="margin-bottom: 10px;">
        <img src="{{ asset('img/logo.png') }}" alt="AgriStock Logo" style="max-height: 42px; width: auto;">
        <button id="closeSidebarBtn" class="btn btn-sm btn-light border-0 d-lg-none" type="button" aria-label="Cerrar menú">
            <i class="fa-solid fa-xmark fs-5 text-muted"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="d-flex flex-column flex-grow-1 overflow-y-auto py-3 px-3">
        <!-- Render specific links based on role if needed, or all for admin -->
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
            <i class="fa-solid fa-border-all"></i>
            <span>Panel</span>
        </a>
        <a href="{{ route('ventas.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('ventas.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-dollar-sign"></i>
            <span>Ventas</span>
        </a>
        <a href="{{ route('pedidos.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('pedidos.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-truck"></i>
            <span>Pedidos</span>
        </a>
        <a href="{{ route('devoluciones.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('devoluciones.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-rotate-left"></i>
            <span>Devoluciones</span>
        </a>
        <a href="{{ route('clientes.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('clientes.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Clientes</span>
        </a>
        <a href="{{ route('compras.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('compras.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Compras</span>
        </a>
        <a href="{{ route('proveedores.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('proveedores.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-truck-moving"></i>
            <span>Proveedores</span>
        </a>
        <a href="{{ route('productos.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('productos.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Inventario</span>
        </a>
        <a href="{{ route('categorias.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('categorias.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categorías</span>
        </a>
        <a href="{{ route('egresos.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('egresos.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span>Gastos</span>
        </a>
        <a href="{{ route('productos.create') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('productos.create') ? 'active-link' : '' }}">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Agregar producto</span>
        </a>
        <a href="{{ route('usuarios.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('usuarios.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-users-gear"></i>
            <span>Usuarios</span>
        </a>
        <a href="{{ route('cupones.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('cupones.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-ticket"></i>
            <span>Cupones</span>
        </a>
        <a href="{{ route('vendedores.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('vendedores.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-user-tie"></i>
            <span>Vendedores</span>
        </a>
        <a href="{{ route('reportes.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('reportes.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-chart-simple"></i>
            <span>Reportes</span>
        </a>
        <a href="{{ route('configuracion.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 {{ request()->routeIs('configuracion.*') ? 'active-link' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Configuración</span>
        </a>
        
        <div style="padding: 15px; margin-top: auto; font-size: 0.8rem; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb;">
            <i class="fa-solid fa-location-dot mb-1"></i><br>
            Calle 3 # 7 22<br>
            Yaguará, Colombia
        </div>
    </nav>
</aside>

<!-- Backdrop para cerrar sidebar en móvil -->
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>

<main class="flex-grow-1 d-flex flex-column min-vh-100 bg-body" style="min-width: 0;">
    <!-- Top Header (Dual Responsive: Native App Header on Mobile, Dashboard Bar on Desktop) -->
    <header class="d-flex align-items-center justify-content-between px-3 px-md-4 py-2 py-md-3 bg-body-tertiary border-bottom position-sticky top-0" style="z-index: 1020;">
        
        <!-- Mobile App Bar (Exact match to video screenshot) -->
        <div class="d-flex d-lg-none align-items-center justify-content-between w-100">
            <!-- Left: User Badge / Brand Avatar -->
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fa-solid fa-chart-line" style="font-size: 0.95rem;"></i>
                </div>
                <div class="d-flex flex-column leading-tight">
                    <span class="fw-bold text-body" style="font-size: 0.95rem; line-height: 1.1;">{{ explode(' ', $nombreCompleto)[0] }}</span>
                    <span class="text-success" style="font-size: 0.68rem; font-weight: 600;">{{ $rol_nombre }}</span>
                </div>
            </div>

            <!-- Right: Action Buttons (Eye hide balance, Theme, Logout) -->
            <div class="d-flex align-items-center gap-2">
                <!-- Eye Toggle (Hide/Show balances) -->
                <button type="button" id="mobileEyeToggleBtn" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" title="Ocultar / Mostrar montos">
                    <i class="fa-solid fa-eye text-body" id="mobileEyeIcon" style="font-size: 0.9rem;"></i>
                </button>

                <!-- Theme Toggle -->
                <button type="button" id="mobileThemeToggleBtn" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" title="Cambiar tema">
                    <i class="fa-solid fa-moon text-body" id="mobileThemeIcon" style="font-size: 0.9rem;"></i>
                </button>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center text-danger shadow-sm" style="width: 36px; height: 36px;" title="Cerrar sesión">
                        <i class="fa-solid fa-power-off" style="font-size: 0.85rem;"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Header Bar -->
        <div class="d-none d-lg-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-3">
                <!-- Eye Toggle Desktop -->
                <button type="button" id="desktopEyeToggleBtn" class="btn btn-sm btn-light border rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm text-body" title="Ocultar o mostrar montos">
                    <i class="fa-solid fa-eye" id="desktopEyeIcon" style="font-size: 0.85rem;"></i>
                    <span style="font-size: 0.75rem; font-weight: 700;">SALDOS</span>
                </button>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Color Home Toggle Desktop -->
                <div id="colorHomeToggle" class="d-flex align-items-center gap-2 border rounded-pill px-3 py-1 bg-light text-muted" style="cursor: pointer; transition: all 0.3s;">
                    <i class="fa-solid fa-moon" id="colorIcon" style="font-size: 0.8rem;"></i>
                    <span id="colorHomeText" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">TEMA OSCURO</span>
                    <span id="colorHomeCircle" class="rounded-circle border shadow-sm bg-light" style="width: 14px; height: 14px; margin-left: 5px; transition: all 0.3s;"></span>
                </div>
                
                <!-- User Profile Dropdown -->
                <div class="d-flex align-items-center gap-2 border rounded-pill px-3 py-1 bg-body-tertiary" style="cursor: pointer;">
                    <span class="fw-bold text-body" style="font-size: 0.85rem;">{{ $rol_nombre }}</span>
                    <div class="rounded-circle border d-flex align-items-center justify-content-center fw-bold shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); width: 26px; height: 26px; font-size: 12px;">
                        {{ strtoupper(substr($nombreCompleto, 0, 1)) }}
                    </div>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger ms-2 p-0" title="Cerrar sesión">
                        <i class="fa-solid fa-power-off fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Content Area Wrapper -->
    <div class="p-3 p-md-4 p-lg-5 flex-grow-1 app-content-body">

<script>
document.addEventListener('DOMContentLoaded', function () {
    var sidebar    = document.getElementById('adminSidebar');
    var backdrop   = document.getElementById('sidebarBackdrop');
    var openBtn    = document.getElementById('openSidebarBtn');
    var closeBtn   = document.getElementById('closeSidebarBtn');

    window.openAdminSidebar = function() {
        if (!sidebar) return;
        sidebar.classList.add('show-sidebar');
        if (backdrop) backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeAdminSidebar = function() {
        if (!sidebar) return;
        sidebar.classList.remove('show-sidebar');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
    };

    if (openBtn) { openBtn.addEventListener('click', window.openAdminSidebar); }
    if (closeBtn) { closeBtn.addEventListener('click', window.closeAdminSidebar); }
    if (backdrop) { backdrop.addEventListener('click', window.closeAdminSidebar); }

    sidebar?.querySelectorAll('a.sidebar-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) { window.closeAdminSidebar(); }
        });
    });

    // Theme Switcher Logic
    const toggleDesktop = document.getElementById('colorHomeToggle');
    const toggleMobile  = document.getElementById('mobileThemeToggleBtn');
    const textDesktop   = document.getElementById('colorHomeText');
    const circleDesktop = document.getElementById('colorHomeCircle');
    const iconDesktop   = document.getElementById('colorIcon');
    const iconMobile    = document.getElementById('mobileThemeIcon');

    function applyTheme(isDark) {
        if (isDark) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            if (textDesktop) textDesktop.innerText = 'TEMA OSCURO';
            if (circleDesktop) circleDesktop.className = 'rounded-circle border shadow-sm bg-light';
            if (iconDesktop) iconDesktop.className = 'fa-solid fa-sun';
            if (iconMobile) iconMobile.className = 'fa-solid fa-sun';
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            if (textDesktop) textDesktop.innerText = 'TEMA CLARO';
            if (circleDesktop) circleDesktop.className = 'rounded-circle border shadow-sm bg-dark';
            if (iconDesktop) iconDesktop.className = 'fa-solid fa-moon';
            if (iconMobile) iconMobile.className = 'fa-solid fa-moon';
        }
    }

    const savedTheme = localStorage.getItem('themeColor') || 'dark';
    applyTheme(savedTheme === 'dark');

    function toggleTheme() {
        const currentlyDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const newTheme = currentlyDark ? 'light' : 'dark';
        localStorage.setItem('themeColor', newTheme);
        applyTheme(!currentlyDark);
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
    }

    toggleDesktop?.addEventListener('click', toggleTheme);
    toggleMobile?.addEventListener('click', toggleTheme);

    // Eye Balance Visibility (Hide/Show Amounts)
    let hideBalances = localStorage.getItem('hideFinancialBalances') === 'true';
    const mobileEyeBtn  = document.getElementById('mobileEyeToggleBtn');
    const mobileEyeIcon = document.getElementById('mobileEyeIcon');
    const deskEyeBtn    = document.getElementById('desktopEyeToggleBtn');
    const deskEyeIcon   = document.getElementById('desktopEyeIcon');

    function updateBalanceEyeUI(hidden) {
        const iconClass = hidden ? 'fa-solid fa-eye-slash text-danger' : 'fa-solid fa-eye text-body';
        if (mobileEyeIcon) mobileEyeIcon.className = iconClass;
        if (deskEyeIcon) deskEyeIcon.className = iconClass;

        document.querySelectorAll('[data-balance-value]').forEach(el => {
            const realVal = el.getAttribute('data-balance-value');
            if (hidden) {
                el.setAttribute('data-visible-text', el.innerText);
                el.innerText = '••••••••';
                el.classList.add('balance-masked');
            } else {
                el.innerText = realVal;
                el.classList.remove('balance-masked');
            }
        });
    }

    function toggleBalances() {
        hideBalances = !hideBalances;
        localStorage.setItem('hideFinancialBalances', hideBalances);
        updateBalanceEyeUI(hideBalances);
        window.dispatchEvent(new CustomEvent('balanceToggled', { detail: { hidden: hideBalances } }));
    }

    mobileEyeBtn?.addEventListener('click', toggleBalances);
    deskEyeBtn?.addEventListener('click', toggleBalances);

    // Apply on load
    updateBalanceEyeUI(hideBalances);
});
</script>