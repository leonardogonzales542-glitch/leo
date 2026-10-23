@php
$currentRoute = request()->route()?->getName() ?? '';
$isDashboard = request()->routeIs('admin.dashboard');
$isVentas = request()->routeIs('ventas.*') || request()->routeIs('pedidos.*');
$isReportes = request()->routeIs('reportes.*');
$isInventario = request()->routeIs('productos.*') || request()->routeIs('categorias.*');
@endphp

<style>
/* Mobile Glassmorphic Bottom Dock (Inspired by Lukas / iOS 18) */
.mobile-bottom-dock {
    position: fixed;
    bottom: 14px;
    left: 14px;
    right: 14px;
    max-width: 440px;
    margin: 0 auto;
    height: 64px;
    background: rgba(15, 23, 42, 0.88);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 35px;
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.45);
    z-index: 1045;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 4px 10px;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.3s ease;
}

[data-bs-theme="light"] .mobile-bottom-dock {
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.dock-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    text-decoration: none;
    color: #94a3b8;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    padding: 6px 12px;
    border-radius: 20px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    cursor: pointer;
    border: 1px solid transparent;
}

.dock-item i {
    font-size: 1.15rem;
    transition: transform 0.25s ease, color 0.25s ease;
}

.dock-item:hover {
    color: #f1f5f9;
}

[data-bs-theme="light"] .dock-item:hover {
    color: #0f172a;
}

/* Active Glow Pill matching the user's video screenshot */
.dock-item.active {
    color: #10b981 !important;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.18), rgba(6, 182, 212, 0.18));
    border: 1px solid rgba(16, 185, 129, 0.35);
    box-shadow: 0 0 16px rgba(16, 185, 129, 0.3);
}

.dock-item.active i {
    transform: translateY(-1px) scale(1.1);
    color: #10b981 !important;
}

[data-bs-theme="light"] .dock-item.active {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));
    border-color: rgba(16, 185, 129, 0.3);
}

/* Press micro-interaction */
.dock-item:active {
    transform: scale(0.92);
}
</style>

<nav class="mobile-bottom-dock d-flex d-lg-none" id="mobileBottomDock" aria-label="Navegación móvil rápida">
    <!-- 1. Inicio -->
    <a href="{{ route('admin.dashboard') }}" class="dock-item {{ $isDashboard ? 'active' : '' }}" title="Dashboard">
        <i class="fa-solid fa-house"></i>
        <span>Inicio</span>
    </a>

    <!-- 2. Ventas / Operaciones -->
    <a href="{{ route('ventas.index') }}" class="dock-item {{ $isVentas ? 'active' : '' }}" title="Ventas">
        <i class="fa-solid fa-wallet"></i>
        <span>Ventas</span>
    </a>

    <!-- 3. Estadísticas (Reportes - Con brillo activo como la captura) -->
    <a href="{{ route('reportes.index') }}" class="dock-item {{ $isReportes ? 'active' : '' }}" title="Estadísticas">
        <i class="fa-solid fa-chart-simple"></i>
        <span>Estadísticas</span>
    </a>

    <!-- 4. Inventario -->
    <a href="{{ route('productos.index') }}" class="dock-item {{ $isInventario ? 'active' : '' }}" title="Inventario">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span>Inventario</span>
    </a>

    <!-- 5. Menú Completo -->
    <button type="button" class="dock-item border-0 bg-transparent" id="dockOpenMenuBtn" title="Menú completo" onclick="if(window.openAdminSidebar){window.openAdminSidebar();}else{document.getElementById('openSidebarBtn')?.click();}">
        <i class="fa-solid fa-bars-staggered"></i>
        <span>Menú</span>
    </button>
</nav>
