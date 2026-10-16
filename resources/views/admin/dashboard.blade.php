@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')

<style>
.metric-card {
    background-color: var(--bs-body-bg);
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s;
    border: 1px solid var(--bs-border-color-translucent);
}
.metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}
.metric-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.25rem;
}
.quick-action-btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}
.alert-stock {
    background-color: #fff1f2;
    border: 1px solid #ffe4e6;
    border-radius: 12px;
    border-left: 4px solid #e11d48;
}
</style>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    @if ($totalStockBajo > 0)
    <div class="alert-stock p-3 d-flex align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-danger" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h6 class="fw-bold text-danger mb-1" style="font-size: 0.9rem;">¡Alerta de Inventario!</h6>
                <p class="mb-0 text-danger opacity-75" style="font-size: 0.8rem;">Tienes <b>{{ $totalStockBajo }} productos</b> que han alcanzado su nivel de stock mínimo.</p>
            </div>
        </div>
        <a href="{{ route('productos.index', ['filtro' => 'stock_bajo']) }}" class="btn btn-sm btn-outline-danger border-0 fw-bold">Revisar Stock</a>
    </div>
    @endif

    <!-- Tarjetas de Métricas Principales -->
    <div class="row g-4">
        <!-- Ventas Totales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="metric-icon bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-10">+5% mes</span>
                </div>
                <h6 class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Ingresos Totales</h6>
                <h3 class="fw-bold text-body mb-0">${{ number_format($montoVentas, 2, ',', '.') }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">{{ $cantidadVentas }} ventas realizadas</span>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Pedidos Registrados</h6>
                <h3 class="fw-bold text-body mb-0">{{ $totalPedidos }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">En cola de procesamiento</span>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="metric-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Clientes Activos</h6>
                <h3 class="fw-bold text-body mb-0">{{ $totalClientes }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">En la base de datos</span>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="metric-card p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="metric-icon bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-semibold mb-1" style="font-size: 0.85rem;">Productos Catálogo</h6>
                <h3 class="fw-bold text-body mb-0">{{ $totalProductos }}</h3>
                <span class="text-muted" style="font-size: 0.75rem;">Listos para la venta</span>
            </div>
        </div>
    </div>

    <!-- Secciones Inferiores -->
    <div class="row g-4 mt-1">
        <!-- Accesos Rápidos -->
        <div class="col-12 col-lg-7">
            <div class="metric-card p-4 h-100">
                <h5 class="fw-bold text-body mb-4" style="font-size: 1.1rem;">Accesos Rápidos</h5>
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <a href="{{ route('ventas.create') }}" class="btn w-100 quick-action-btn shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color);">
                            <i class="fa-solid fa-cart-plus text-primary"></i> Nueva Venta
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="{{ route('productos.create') }}" class="btn w-100 quick-action-btn shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color);">
                            <i class="fa-solid fa-plus-circle text-success"></i> Crear Producto
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="{{ route('clientes.create') }}" class="btn w-100 quick-action-btn shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color);">
                            <i class="fa-solid fa-user-plus text-warning"></i> Nuevo Cliente
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="{{ route('pedidos.index') }}" class="btn w-100 quick-action-btn shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color);">
                            <i class="fa-solid fa-box-open text-info"></i> Ver Pedidos
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="{{ route('reportes.index') }}" class="btn w-100 quick-action-btn shadow-sm" style="background-color: var(--bs-tertiary-bg); color: var(--bs-body-color); border: 1px solid var(--bs-border-color);">
                            <i class="fa-solid fa-chart-line text-danger"></i> Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado del Sistema -->
        <div class="col-12 col-lg-5">
            <div class="metric-card p-4 h-100 bg-dark text-white border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <div class="position-absolute opacity-10" style="right: -20px; top: -20px;">
                    <i class="fa-solid fa-server" style="font-size: 150px;"></i>
                </div>
                <h5 class="fw-bold text-white mb-4 position-relative" style="font-size: 1.1rem; z-index: 1;">Estado del Sistema</h5>
                
                <div class="d-flex flex-column gap-3 position-relative" style="z-index: 1;">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
                        <div class="d-flex align-items-center gap-3">
                            <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                            <span class="fw-medium text-light" style="font-size: 0.85rem;">Conexión BD (tiendadb)</span>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25">Estable</span>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-shield-halved text-info"></i>
                            <span class="fw-medium text-light" style="font-size: 0.85rem;">Seguridad Activa</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.8rem;">Sesión Protegida</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-regular fa-clock text-warning"></i>
                            <span class="fw-medium text-light" style="font-size: 0.85rem;">Sincronización</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.8rem;">Actualizado (En vivo)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevas Secciones -->
    <div class="row g-4 mt-4">
        <!-- Últimas Ventas -->
        <div class="col-12 col-lg-8">
            <div class="metric-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-body mb-0" style="font-size: 1.1rem;">Últimas Ventas</h5>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Ver todas</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Datos de ejemplo -->
                            <tr>
                                <td><span class="fw-medium">#FAC-001</span></td>
                                <td>Juan Pérez</td>
                                <td>Hoy, 10:30 AM</td>
                                <td class="fw-semibold text-success">$150.00</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Completada</span></td>
                            </tr>
                            <tr>
                                <td><span class="fw-medium">#FAC-002</span></td>
                                <td>María García</td>
                                <td>Hoy, 09:15 AM</td>
                                <td class="fw-semibold text-success">$85.50</td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Completada</span></td>
                            </tr>
                            <tr>
                                <td><span class="fw-medium">#FAC-003</span></td>
                                <td>Carlos López</td>
                                <td>Ayer, 16:45 PM</td>
                                <td class="fw-semibold text-success">$320.00</td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Pendiente</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alertas de Inventario -->
        <div class="col-12 col-lg-4">
            <div class="metric-card p-4 h-100">
                <h5 class="fw-bold text-body mb-4" style="font-size: 1.1rem;">Alertas Stock</h5>
                <div class="list-group list-group-flush gap-2">
                    <!-- Datos de ejemplo -->
                    <div class="list-group-item px-3 py-3 border rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--bs-tertiary-bg);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">Fertilizante NPK</h6>
                                <small class="text-danger fw-medium">Quedan: 5 un.</small>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item px-3 py-3 border rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--bs-tertiary-bg);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">Semillas de Maíz</h6>
                                <small class="text-warning fw-medium">Quedan: 12 un.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('productos.index') }}" class="text-decoration-none text-primary fw-medium" style="font-size: 0.9rem;">Ir al inventario <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa de Ubicación en el Dashboard -->
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="metric-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-body mb-0" style="font-size: 1.1rem;">
                        <i class="fa-solid fa-map-location-dot text-primary me-2"></i> Nuestra Ubicación Física
                    </h5>
                </div>
                <div style="border-radius: 12px; overflow: hidden; border: 1px solid var(--bs-border-color-translucent);">
                    <iframe 
                        src="https://www.google.com/maps?q=Calle+3+%23+7+22,+Yaguara,+Colombia&output=embed" 
                        width="100%" 
                        height="400" 
                        style="border:0; display: block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
