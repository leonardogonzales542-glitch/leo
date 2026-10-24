<style>
/* ========================================================
   AGRISTOCK MODERN APP FOOTER
   ======================================================== */
.app-system-footer {
    border-top: 1px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    transition: background-color 0.3s ease, border-color 0.3s ease;
    font-size: 0.875rem;
    position: relative;
    z-index: 10;
}

[data-bs-theme="dark"] .app-system-footer {
    background: rgba(15, 23, 42, 0.75);
    border-top-color: rgba(255, 255, 255, 0.08);
}

[data-bs-theme="light"] .app-system-footer {
    background: #ffffff;
    border-top-color: rgba(0, 0, 0, 0.08);
}

.footer-brand-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.footer-link {
    color: var(--bs-secondary-color);
    text-decoration: none;
    transition: color 0.2s ease, transform 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 0;
}

.footer-link:hover {
    color: #10b981;
    transform: translateX(3px);
}

.footer-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.footer-pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: footerPulse 2s infinite;
}

@keyframes footerPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

.footer-btn-scroll {
    width: 34px;
    height: 34px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: transform 0.2s ease, background-color 0.2s ease;
}

.footer-btn-scroll:hover {
    transform: translateY(-2px);
    background-color: #10b981;
    border-color: #10b981;
    color: #ffffff !important;
}

/* Espacio seguro en dispositivos móviles para no colisionar con mobile-bottom-dock */
@media (max-width: 991.98px) {
    .app-system-footer {
        padding-bottom: 95px !important;
    }
}

@media (max-width: 991.98px) and (orientation: landscape), (max-height: 520px) {
    .app-system-footer {
        padding-bottom: 75px !important;
    }
}
</style>

<footer class="app-system-footer mt-auto py-4 px-3 px-md-4">
    <div class="container-fluid px-0">
        <div class="row g-4 mb-4">
            {{-- Columna 1: Información de la Empresa & Branding --}}
            <div class="col-12 col-md-6 col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="footer-brand-badge">
                        <i class="fa-solid fa-seedling fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-body" style="letter-spacing: -0.3px;">
                            AgriStock <span class="text-success fw-semibold">Purina</span>
                        </h6>
                        <span class="text-body-secondary small" style="font-size: 0.75rem;">
                            Gestión Agropecuaria & Nutrición Animal
                        </span>
                    </div>
                </div>

                <p class="text-body-secondary small mb-3" style="max-width: 440px; line-height: 1.55;">
                    Plataforma especializada en control de inventario de purinas, alimentos balanceados y trazabilidad de lotes con fechas de vencimiento, facturación rápida y control de proveedores.
                </p>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="footer-status-pill">
                        <span class="footer-pulse-dot"></span>
                        <span>Sistema Operativo • v2.4 Pro</span>
                    </div>
                    <span class="badge bg-body-secondary text-body-secondary border rounded-pill px-2 py-1 small">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> SSL Protegido
                    </span>
                </div>
            </div>

            {{-- Columna 2: Enlaces Rápidos del Sistema --}}
            <div class="col-6 col-md-3 col-lg-3">
                <h6 class="fw-bold text-body text-uppercase small mb-3" style="letter-spacing: 0.5px;">
                    Accesos Rápidos
                </h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    @if(auth()->check() && auth()->user()->id_rol == 1)
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="footer-link">
                                <i class="fa-solid fa-chart-line text-success"></i> Panel Principal
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('productos.index') }}" class="footer-link">
                                <i class="fa-solid fa-boxes-stacked text-primary"></i> Inventario & Purinas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('proveedores.index') }}" class="footer-link">
                                <i class="fa-solid fa-truck-moving text-warning"></i> Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('clientes.index') }}" class="footer-link">
                                <i class="fa-solid fa-users text-info"></i> Clientes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reportes.index') }}" class="footer-link">
                                <i class="fa-solid fa-file-invoice-dollar text-danger"></i> Reportes Financieros
                            </a>
                        </li>
                    @elseif(auth()->check() && auth()->user()->id_rol == 2)
                        <li>
                            <a href="{{ route('vendedor.dashboard') }}" class="footer-link">
                                <i class="fa-solid fa-cash-register text-success"></i> Punto de Venta
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('productos.index') }}" class="footer-link">
                                <i class="fa-solid fa-boxes-stacked text-primary"></i> Consultar Stock
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('clientes.index') }}" class="footer-link">
                                <i class="fa-solid fa-users text-info"></i> Clientes
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('cliente.dashboard') }}" class="footer-link">
                                <i class="fa-solid fa-bag-shopping text-success"></i> Mis Compras
                            </a>
                        </li>
                        <li>
                            <a href="/" class="footer-link">
                                <i class="fa-solid fa-house text-primary"></i> Inicio
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Columna 3: Información de Contacto, WhatsApp y Ubicación --}}
            <div class="col-12 col-md-3 col-lg-4">
                <h6 class="fw-bold text-body text-uppercase small mb-3" style="letter-spacing: 0.5px;">
                    Contacto & Soporte
                </h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                    <li class="d-flex align-items-start gap-2 text-body-secondary">
                        <i class="fa-solid fa-location-dot text-danger mt-1"></i>
                        <span>
                            <strong class="text-body d-block">Sede Principal:</strong>
                            Calle 3 #7-22, Yaguará, Huila, Colombia
                        </span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-success fs-6"></i>
                        <a href="https://wa.me/573222673734?text=Hola%20AgriStock,%20solicito%20asistencia%20en%20el%20sistema" target="_blank" class="footer-link fw-semibold text-success">
                            +57 322 267 3734
                        </a>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-body-secondary">
                        <i class="fa-solid fa-envelope text-primary"></i>
                        <span>soporte@agristock.com</span>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-body-secondary">
                        <i class="fa-solid fa-clock text-warning"></i>
                        <span>Lun - Sáb: 7:00 AM - 6:00 PM</span>
                    </li>
                </ul>

                <div class="mt-3">
                    <a href="https://wa.me/573222673734?text=Hola%20AgriStock,%20necesito%20ayuda" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 d-inline-flex align-items-center gap-2">
                        <i class="fa-brands fa-whatsapp"></i> Chat de Ayuda
                    </a>
                </div>
            </div>
        </div>

        {{-- Barra Inferior de Copyright & Créditos --}}
        <div class="pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-body-secondary small" style="border-top-color: var(--bs-border-color) !important; font-size: 0.8rem;">
            <div>
                &copy; {{ date('Y') }} <strong class="text-body">AgriStock</strong> Purina. Todos los derechos reservados.
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-md-inline text-body-tertiary">
                    Tecnología para el Agro y Mascotas
                </span>
                <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="btn btn-sm btn-light border text-body-secondary footer-btn-scroll" title="Volver arriba">
                    <i class="fa-solid fa-arrow-up" style="font-size: 0.75rem;"></i>
                </button>
            </div>
        </div>
    </div>
</footer>
