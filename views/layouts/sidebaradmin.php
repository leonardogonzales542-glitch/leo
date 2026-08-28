<?php
$id_rol = $usuario['id_rol'];
$rol_nombre = '';
switch ($id_rol) {
    case '1':
        $rol_nombre = 'Admin Principal';
        break;
    case '2':
        $rol_nombre = 'Vendedor';
        break;
    case '3':
        $rol_nombre = 'Cliente';
        break;
}
$nombreCompleto = $usuario['usuario'];
$currentPage = basename($_SERVER['PHP_SELF']);
?>

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
</style>

<aside class="flex-shrink-0 bg-body-tertiary border-end d-flex flex-column position-sticky top-0 vh-100" style="width: 250px; min-width: 250px;">
    <!-- Brand Logo Section -->
    <div class="d-flex flex-column align-items-center justify-content-center bg-white" style="padding: 20px 0; margin-bottom: 20px;">
        <img src="../../public/img/logo.png" alt="Purinas Bet Logo" style="max-width: 150px; height: auto;">
    </div>

    <!-- Navigation Menu -->
    <nav class="d-flex flex-column flex-grow-1 overflow-y-auto py-3 px-3">
        <!-- Render specific links based on role if needed, or all for admin -->
        <a href="dashboard.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'dashboard.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-border-all"></i>
            <span>Panel</span>
        </a>
        <a href="ventas.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'ventas.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-dollar-sign"></i>
            <span>Ventas</span>
        </a>
        <a href="pedidos.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'pedidos.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-truck"></i>
            <span>Pedidos</span>
        </a>
        <a href="devoluciones.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'devoluciones.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-rotate-left"></i>
            <span>Devoluciones</span>
        </a>
        <a href="clientes.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'clientes.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-users"></i>
            <span>Clientes</span>
        </a>
        <a href="proveedores.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'compras.php' || $currentPage == 'proveedores.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Compras</span>
        </a>
        <a href="inventario.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'inventario.php' || $currentPage == 'productos.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>Inventario</span>
        </a>
        <a href="categorias.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'categorias.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categorías</span>
        </a>
        <a href="agregar_producto.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'agregar_producto.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Agregar producto</span>
        </a>
        <a href="administrar.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'administrar.php' || $currentPage == 'gusuarios.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-user-shield"></i>
            <span>Administrar</span>
        </a>
        <a href="cupones.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'cupones.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-ticket"></i>
            <span>Cupones</span>
        </a>
        <a href="vendedores.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'vendedores.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-user-tie"></i>
            <span>Vendedores</span>
        </a>
        <a href="reportes.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'reportes.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-chart-simple"></i>
            <span>Reportes</span>
        </a>
        <a href="configuracion.php" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none mb-1 <?= $currentPage == 'configuracion.php' ? 'active-link' : '' ?>">
            <i class="fa-solid fa-gear"></i>
            <span>Configuración</span>
        </a>
    </nav>
</aside>

<main class="flex-grow-1 d-flex flex-column min-vh-100 bg-body" style="min-width: 0;">
    <!-- Top Header -->
    <header class="d-flex align-items-center justify-content-end px-4 py-3 bg-body-tertiary border-bottom position-sticky top-0" style="z-index: 1020;">
        <div class="d-flex align-items-center gap-4">
            <!-- Color Home Toggle -->
            <div id="colorHomeToggle" class="d-flex align-items-center gap-2 border rounded-pill px-3 py-1 bg-light text-muted" style="cursor: pointer; transition: all 0.3s;">
                <i class="fa-solid fa-moon" id="colorIcon" style="font-size: 0.8rem;"></i>
                <span id="colorHomeText" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">TEMA CLARO</span>
                <span id="colorHomeCircle" class="rounded-circle border shadow-sm bg-dark" style="width: 14px; height: 14px; margin-left: 5px; transition: all 0.3s;"></span>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('colorHomeToggle');
                const text = document.getElementById('colorHomeText');
                const circle = document.getElementById('colorHomeCircle');
                const icon = document.getElementById('colorIcon');
                
                function setDarkMode(isDark) {
                    if (isDark) {
                        document.documentElement.setAttribute('data-bs-theme', 'dark');
                        text.innerText = 'TEMA OSCURO';
                        circle.className = 'rounded-circle border shadow-sm bg-light';
                        icon.className = 'fa-solid fa-sun';
                        
                        // Fallback manual toggle for elements that might have hardcoded bg-white
                        document.querySelectorAll('.bg-white, .text-dark').forEach(el => {
                            if (!el.id.includes('colorHomeCircle') && !el.closest('header') && !el.closest('aside')) {
                                if (el.classList.contains('bg-white')) {
                                    el.classList.remove('bg-white');
                                    el.classList.add('bg-dark', 'text-light', 'was-bg-white');
                                }
                                if (el.classList.contains('text-dark')) {
                                    el.classList.remove('text-dark');
                                    el.classList.add('text-body', 'was-text-dark');
                                }
                            }
                        });
                    } else {
                        document.documentElement.setAttribute('data-bs-theme', 'light');
                        text.innerText = 'TEMA CLARO';
                        circle.className = 'rounded-circle border shadow-sm bg-dark';
                        icon.className = 'fa-solid fa-moon';
                        
                        // Revert fallback manual toggle
                        document.querySelectorAll('.was-bg-white, .was-text-dark').forEach(el => {
                            if (el.classList.contains('was-bg-white')) {
                                el.classList.remove('bg-dark', 'text-light', 'was-bg-white');
                                el.classList.add('bg-white');
                            }
                            if (el.classList.contains('was-text-dark')) {
                                el.classList.remove('text-body', 'was-text-dark');
                                el.classList.add('text-dark');
                            }
                        });
                    }
                }

                // Load saved preference
                const savedTheme = localStorage.getItem('themeColor');
                const isDark = savedTheme === 'dark';
                setDarkMode(isDark);

                toggle.addEventListener('click', function() {
                    const currentlyDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    localStorage.setItem('themeColor', currentlyDark ? 'light' : 'dark');
                    setDarkMode(!currentlyDark);
                });
            });
            </script>
            
            <!-- User Profile Dropdown -->
            <div class="d-flex align-items-center gap-3 border rounded-pill px-3 py-1 bg-body-tertiary" style="cursor: pointer;">
                <span class="fw-bold text-body" style="font-size: 0.85rem;"><?= htmlspecialchars($rol_nombre) ?></span>
                <div class="rounded-circle border d-flex align-items-center justify-content-center fw-bold shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); width: 26px; height: 26px; font-size: 12px;">
                    <?= strtoupper(substr($nombreCompleto, 0, 1)) ?>
                </div>
            </div>
            
            <a href="../../controllers/auth/authController.php?action=logout" class="text-danger ms-2" title="Cerrar sesión">
                <i class="fa-solid fa-power-off fs-5"></i>
            </a>
        </div>
    </header>

    <!-- Content Area Wrapper -->
    <div class="p-4 p-lg-5 flex-grow-1">
