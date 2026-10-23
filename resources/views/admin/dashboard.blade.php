@extends('layouts.app')
@section('title', 'Dashboard - AgriStock Mobile & Web')
@section('content')

<style>
/* ========================================================
   FINTECH MOBILE-FIRST STYLES (Lukas / iOS 18 Glassmorphism)
   ======================================================== */
:root {
    --fintech-card-bg: rgba(17, 24, 39, 0.75);
    --fintech-card-border: rgba(255, 255, 255, 0.08);
    --fintech-green: #10b981;
    --fintech-green-glow: rgba(16, 185, 129, 0.35);
    --fintech-red: #ef4444;
    --fintech-red-glow: rgba(239, 68, 68, 0.35);
}

[data-bs-theme="light"] {
    --fintech-card-bg: rgba(255, 255, 255, 0.85);
    --fintech-card-border: rgba(0, 0, 0, 0.08);
}

.fintech-card {
    background: var(--fintech-card-bg);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--fintech-card-border);
    border-radius: 22px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
}

[data-bs-theme="light"] .fintech-card {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.fintech-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
}

/* Micro-Bar Monthly Selector Component (Exact Match to Screenshot) */
.month-selector-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--fintech-card-border);
    font-size: 0.88rem;
    font-weight: 600;
}

.month-chevron-btn {
    background: transparent;
    border: none;
    color: var(--bs-body-color);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
    cursor: pointer;
}

.month-chevron-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Dual Mini Bars Container */
.mini-bars-container {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    height: 90px;
    padding: 10px 10px 0 10px;
    gap: 8px;
}

.mini-bar-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    height: 100%;
    justify-content: flex-end;
    cursor: pointer;
    transition: transform 0.2s;
}

.mini-bar-column:hover {
    transform: scale(1.05);
}

.mini-bar-pair {
    display: flex;
    align-items: flex-end;
    gap: 3px;
    height: 60px;
    width: 100%;
    justify-content: center;
}

.bar-pill {
    width: 6px;
    max-width: 8px;
    border-radius: 10px;
    transition: height 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.bar-pill.green {
    background: linear-gradient(180deg, #10b981 0%, #059669 100%);
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
}

.bar-pill.red {
    background: linear-gradient(180deg, #ef4444 0%, #b91c1c 100%);
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
}

.mini-bar-label {
    font-size: 0.72rem;
    font-weight: 600;
    margin-top: 6px;
    color: #94a3b8;
}

.mini-bar-column.current-month .mini-bar-label {
    color: #10b981;
    font-weight: 800;
}

/* Eye Masked text */
.balance-masked {
    letter-spacing: 2px;
    font-family: monospace;
    opacity: 0.85;
}

/* Chart Canvas wrapper */
.chart-box-wrapper {
    position: relative;
    height: 220px;
    width: 100%;
}

@media (min-width: 768px) {
    .chart-box-wrapper {
        height: 250px;
    }
}

/* Quick Action Buttons Mobile */
.action-icon-pill {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    transition: transform 0.2s;
}

.action-pill-card {
    text-decoration: none;
    background: var(--fintech-card-bg);
    border: 1px solid var(--fintech-card-border);
    border-radius: 18px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--bs-body-color);
    transition: transform 0.2s, border-color 0.2s, background-color 0.2s;
}

.action-pill-card:hover {
    transform: translateY(-2px);
    border-color: rgba(16, 185, 129, 0.4);
    color: var(--bs-body-color);
}
</style>

<div class="d-flex flex-column gap-3 gap-md-4" style="max-width: 1180px; margin: 0 auto; width: 100%;">

    <!-- 1. Alerta de Stock Bajo (si existe) -->
    @if ($totalStockBajo > 0)
    <div class="fintech-card p-3 d-flex align-items-center justify-content-between border-danger border-opacity-50" style="background: rgba(239, 68, 68, 0.08);">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-danger" style="width: 38px; height: 38px; background: rgba(239, 68, 68, 0.15);">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h6 class="fw-bold text-danger mb-0" style="font-size: 0.88rem;">Atención: Inventario Crítico</h6>
                <p class="mb-0 text-body-secondary" style="font-size: 0.78rem;"><b>{{ $totalStockBajo }} productos</b> están en o bajo su stock mínimo.</p>
            </div>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" style="font-size: 0.78rem;">Revisar</a>
    </div>
    @endif

    <!-- 2. Tarjeta Principal: Selector de Mes con Barritas Duales (Idéntico a la Captura) -->
    <div class="fintech-card p-3 p-md-4">
        <!-- Barra de Mes Interactiva con Flechas < Octubre de 2026 > -->
        <div class="month-selector-bar mb-3">
            <button type="button" class="month-chevron-btn" id="btnPrevMonth" title="Mes anterior">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="d-flex align-items-center gap-2 text-body">
                <i class="fa-regular fa-calendar text-success"></i>
                <span id="activeMonthDisplay" class="fw-bold">{{ $mesActualNombre }}</span>
            </div>
            <button type="button" class="month-chevron-btn" id="btnNextMonth" title="Mes siguiente">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <!-- Micro-Barras de 6 Meses (May, Jun, Jul, Ago, Sept, Oct) -->
        <div class="mini-bars-container">
            @foreach($mesesData as $m)
            <div class="mini-bar-column {{ $m['is_current'] ? 'current-month' : '' }}" 
                 title="{{ $m['label'] }}: Ingresos ${{ number_format($m['ingresos'], 0, ',', '.') }} | Gastos ${{ number_format($m['gastos'], 0, ',', '.') }}"
                 onclick="highlightMonth('{{ $m['label'] }}', {{ $m['ingresos'] }}, {{ $m['gastos'] }})">
                <div class="mini-bar-pair">
                    <!-- Barra Verde: Ingresos -->
                    <div class="bar-pill green" style="height: {{ $m['pct_ingresos'] }}%;"></div>
                    <!-- Barra Roja: Gastos -->
                    <div class="bar-pill red" style="height: {{ $m['pct_gastos'] }}%;"></div>
                </div>
                <span class="mini-bar-label">{{ $m['label'] }}</span>
            </div>
            @endforeach
        </div>

        <!-- Leyenda: Ingresos vs Gastos -->
        <div class="d-flex align-items-center justify-content-center gap-4 mt-2 pt-2 border-top border-opacity-10" style="font-size: 0.75rem;">
            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #10b981; box-shadow: 0 0 6px #10b981;"></span>
                <span class="text-body-secondary fw-semibold">Ingresos</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #ef4444; box-shadow: 0 0 6px #ef4444;"></span>
                <span class="text-body-secondary fw-semibold">Gastos</span>
            </div>
        </div>
    </div>

    <!-- 3. Grid de KPIs Financieros con Enmascarado de Saldos (Eye Toggle) -->
    <div class="row g-3">
        <!-- Balance Neto -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="fintech-card p-3 p-md-4 h-100 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-body-secondary fw-semibold" style="font-size: 0.8rem;">Balance Neto</span>
                    <span class="badge bg-success bg-opacity-20 text-success rounded-pill border border-success border-opacity-25" style="font-size: 0.7rem;">+12% mes</span>
                </div>
                <h3 class="fw-bold text-body mb-1" style="font-size: 1.55rem;">
                    <span data-balance-value="${{ number_format($balanceNeto, 2, ',', '.') }}">${{ number_format($balanceNeto, 2, ',', '.') }}</span>
                </h3>
                <span class="text-body-secondary" style="font-size: 0.75rem;">Ingresos netos disponibles</span>
            </div>
        </div>

        <!-- Ingresos Totales (Ventas) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="fintech-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-body-secondary fw-semibold" style="font-size: 0.8rem;">Ingresos (Ventas)</span>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-success" style="width: 28px; height: 28px; background: rgba(16, 185, 129, 0.15);">
                        <i class="fa-solid fa-arrow-down-left" style="font-size: 0.8rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-1" style="font-size: 1.55rem;">
                    <span data-balance-value="${{ number_format($montoVentas, 2, ',', '.') }}">${{ number_format($montoVentas, 2, ',', '.') }}</span>
                </h3>
                <span class="text-body-secondary" style="font-size: 0.75rem;">{{ $cantidadVentas }} ventas registradas</span>
            </div>
        </div>

        <!-- Gastos Totales (Egresos) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="fintech-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-body-secondary fw-semibold" style="font-size: 0.8rem;">Gastos (Egresos)</span>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-danger" style="width: 28px; height: 28px; background: rgba(239, 68, 68, 0.15);">
                        <i class="fa-solid fa-arrow-up-right" style="font-size: 0.8rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-1" style="font-size: 1.55rem;">
                    <span data-balance-value="${{ number_format($montoGastos, 2, ',', '.') }}">${{ number_format($montoGastos, 2, ',', '.') }}</span>
                </h3>
                <span class="text-body-secondary" style="font-size: 0.75rem;">Costos y compras operativas</span>
            </div>
        </div>

        <!-- Pedidos e Inventario -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="fintech-card p-3 p-md-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-body-secondary fw-semibold" style="font-size: 0.8rem;">Operaciones</span>
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-info" style="width: 28px; height: 28px; background: rgba(6, 182, 212, 0.15);">
                        <i class="fa-solid fa-truck" style="font-size: 0.8rem;"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-body mb-1" style="font-size: 1.55rem;">{{ $totalPedidos }}</h3>
                <div class="d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                    <span class="text-body-secondary">{{ $totalProductos }} productos</span>
                    <span>•</span>
                    <span class="text-warning fw-semibold">{{ $totalClientes }} clientes</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Gráficos Móviles Fintech (Tendencia de Gastos & Balance Futuro) -->
    <div class="row g-3">
        <!-- Gráfico 1: "Tendencia de Gastos / Ventas" (Exacto al vídeo) -->
        <div class="col-12 col-lg-6">
            <div class="fintech-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-body mb-0" style="font-size: 0.95rem;">Tendencia de Gastos</h6>
                        <span class="text-body-secondary" style="font-size: 0.75rem;">Historial mensual comparativo</span>
                    </div>
                    <!-- Píldoras de Filtro del Gráfico -->
                    <div class="btn-group btn-group-sm rounded-pill p-1 bg-body border" role="group">
                        <button type="button" class="btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold text-danger active" id="btnFilterGastos" onclick="setTrendDataset('gastos')">Gastos</button>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold text-body-secondary" id="btnFilterIngresos" onclick="setTrendDataset('ingresos')">Ingresos</button>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold text-body-secondary" id="btnFilterAmbos" onclick="setTrendDataset('ambos')">Ambos</button>
                    </div>
                </div>

                <div class="chart-box-wrapper">
                    <canvas id="tendenciaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico 2: "Balance Futuro" (Proyección a 90 días) -->
        <div class="col-12 col-lg-6">
            <div class="fintech-card p-3 p-md-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-body mb-0" style="font-size: 0.95rem;">Balance Futuro</h6>
                        <span class="text-body-secondary" style="font-size: 0.75rem;">Proyección financiera a 90 días</span>
                    </div>
                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-1 border border-success border-opacity-25" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-arrow-trend-up me-1"></i> +45% est.
                    </span>
                </div>

                <div class="chart-box-wrapper">
                    <canvas id="balanceFuturoChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Accesos Rápidos Táctiles (Mobile Quick Actions) -->
    <div class="fintech-card p-3 p-md-4">
        <h6 class="fw-bold text-body mb-3" style="font-size: 0.92rem;">Accesos Rápidos</h6>
        <div class="row g-2 g-md-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('ventas.create') }}" class="action-pill-card">
                    <div class="action-icon-pill" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <i class="fa-solid fa-cart-plus"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold" style="font-size: 0.85rem;">Nueva Venta</span>
                        <span class="text-body-secondary" style="font-size: 0.7rem;">Facturación</span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('egresos.index') }}" class="action-pill-card">
                    <div class="action-icon-pill" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold" style="font-size: 0.85rem;">Egresos</span>
                        <span class="text-body-secondary" style="font-size: 0.7rem;">Control gastos</span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('productos.create') }}" class="action-pill-card">
                    <div class="action-icon-pill" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold" style="font-size: 0.85rem;">Producto</span>
                        <span class="text-body-secondary" style="font-size: 0.7rem;">Agregar stock</span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('clientes.create') }}" class="action-pill-card">
                    <div class="action-icon-pill" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold" style="font-size: 0.85rem;">Nuevo Cliente</span>
                        <span class="text-body-secondary" style="font-size: 0.7rem;">Registro</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- 6. Transacciones Recientes (Diseño de Tarjetas Táctiles en Móvil y Tabla en Desktop) -->
    <div class="fintech-card p-3 p-md-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="fw-bold text-body mb-0" style="font-size: 0.95rem;">Últimas Transacciones</h6>
                <span class="text-body-secondary" style="font-size: 0.75rem;">Movimientos recientes de ventas</span>
            </div>
            <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold text-body" style="font-size: 0.78rem;">Ver todas</a>
        </div>

        @if($ultimasVentas && count($ultimasVentas) > 0)
        <!-- Vista Móvil: Lista de tarjetas tipo App bancaria -->
        <div class="d-flex d-md-none flex-column gap-2">
            @foreach($ultimasVentas as $v)
            <div class="p-3 rounded-4 border border-opacity-10 bg-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-success" style="width: 38px; height: 38px; background: rgba(16, 185, 129, 0.12);">
                        <i class="fa-solid fa-arrow-down-left" style="font-size: 0.9rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-body" style="font-size: 0.88rem;">{{ $v->cliente_nombre ?? 'Cliente General' }}</h6>
                        <span class="text-body-secondary" style="font-size: 0.72rem;">{{ $v->numero_factura }} • {{ \Carbon\Carbon::parse($v->fecha)->format('d M, h:i A') }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-success d-block" style="font-size: 0.95rem;" data-balance-value="+${{ number_format($v->total, 2, ',', '.') }}">+${{ number_format($v->total, 2, ',', '.') }}</span>
                    <span class="badge bg-secondary bg-opacity-20 text-body-secondary rounded-pill" style="font-size: 0.65rem;">{{ $v->metodo_pago ?? 'Efectivo' }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Vista Desktop: Tabla limpia -->
        <div class="d-none d-md-block table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-body-secondary" style="font-size: 0.8rem;">
                    <tr>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.88rem;">
                    @foreach($ultimasVentas as $v)
                    <tr>
                        <td class="fw-semibold text-body">{{ $v->numero_factura }}</td>
                        <td>{{ $v->cliente_nombre ?? 'Cliente General' }}</td>
                        <td class="text-body-secondary">{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y h:i A') }}</td>
                        <td><span class="badge bg-secondary bg-opacity-15 text-body rounded-pill">{{ $v->metodo_pago ?? 'Efectivo' }}</span></td>
                        <td class="fw-bold text-success"><span data-balance-value="${{ number_format($v->total, 2, ',', '.') }}">${{ number_format($v->total, 2, ',', '.') }}</span></td>
                        <td><span class="badge bg-success bg-opacity-20 text-success rounded-pill border border-success border-opacity-25">{{ $v->estado }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-body-secondary">
            <i class="fa-solid fa-inbox fs-2 opacity-50 mb-2"></i>
            <p class="mb-0" style="font-size: 0.85rem;">No hay ventas registradas aún.</p>
        </div>
        @endif
    </div>

</div>

<!-- Scripts de Gráficos e Interactividad Móvil -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = @json($chartLabels);
    const dataGastos = @json($chartGastos);
    const dataIngresos = @json($chartIngresos);
    const proyLabels = @json($chartProyeccionLabels);
    const proyData = @json($chartProyeccionData);

    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.06)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // 1. Gráfico de "Tendencia de Gastos / Ventas" (Spline con Tooltip Estilo Lukas)
    const ctxTrend = document.getElementById('tendenciaChart').getContext('2d');
    
    // Gradiente Rojo para Gastos
    const gradRed = ctxTrend.createLinearGradient(0, 0, 0, 200);
    gradRed.addColorStop(0, 'rgba(239, 68, 68, 0.35)');
    gradRed.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

    // Gradiente Verde para Ingresos
    const gradGreen = ctxTrend.createLinearGradient(0, 0, 0, 200);
    gradGreen.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    gradGreen.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    window.tendenciaChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Gastos',
                    data: dataGastos,
                    borderColor: '#ef4444',
                    backgroundColor: gradRed,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#0f172a',
                    bodyColor: '#ef4444',
                    bodyFont: { weight: 'bold', size: 13 },
                    titleFont: { weight: 'bold', size: 12 },
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + Number(context.raw).toLocaleString('es-CO');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: textColor, font: { size: 11, weight: '600' } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: textColor,
                        font: { size: 10 },
                        callback: function(val) {
                            if (val >= 1000000) return (val/1000000) + 'M';
                            if (val >= 1000) return (val/1000) + 'k';
                            return val;
                        }
                    }
                }
            }
        }
    });

    // Filtros del Gráfico de Tendencia
    window.setTrendDataset = function(type) {
        document.getElementById('btnFilterGastos').className = 'btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold ' + (type === 'gastos' ? 'text-danger active' : 'text-body-secondary');
        document.getElementById('btnFilterIngresos').className = 'btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold ' + (type === 'ingresos' ? 'text-success active' : 'text-body-secondary');
        document.getElementById('btnFilterAmbos').className = 'btn btn-sm btn-link text-decoration-none py-0 px-2 fw-semibold ' + (type === 'ambos' ? 'text-primary active' : 'text-body-secondary');

        if (type === 'gastos') {
            window.tendenciaChart.data.datasets = [{
                label: 'Gastos',
                data: dataGastos,
                borderColor: '#ef4444',
                backgroundColor: gradRed,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7
            }];
            window.tendenciaChart.options.plugins.tooltip.bodyColor = '#ef4444';
        } else if (type === 'ingresos') {
            window.tendenciaChart.data.datasets = [{
                label: 'Ingresos',
                data: dataIngresos,
                borderColor: '#10b981',
                backgroundColor: gradGreen,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7
            }];
            window.tendenciaChart.options.plugins.tooltip.bodyColor = '#10b981';
        } else {
            window.tendenciaChart.data.datasets = [
                {
                    label: 'Ingresos',
                    data: dataIngresos,
                    borderColor: '#10b981',
                    backgroundColor: gradGreen,
                    borderWidth: 2.5,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointRadius: 4
                },
                {
                    label: 'Gastos',
                    data: dataGastos,
                    borderColor: '#ef4444',
                    backgroundColor: gradRed,
                    borderWidth: 2.5,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointRadius: 4
                }
            ];
            window.tendenciaChart.options.plugins.tooltip.bodyColor = '#0f172a';
        }
        window.tendenciaChart.update();
    };

    // 2. Gráfico de "Balance Futuro" (Línea de Proyección Ascendente Verde Brillante)
    const ctxFuturo = document.getElementById('balanceFuturoChart').getContext('2d');
    const gradFuturo = ctxFuturo.createLinearGradient(0, 0, 0, 200);
    gradFuturo.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradFuturo.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    window.balanceFuturoChart = new Chart(ctxFuturo, {
        type: 'line',
        data: {
            labels: proyLabels,
            datasets: [{
                label: 'Balance Proyectado',
                data: proyData,
                borderColor: '#10b981',
                backgroundColor: gradFuturo,
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#10b981',
                    bodyFont: { weight: 'bold', size: 13 },
                    padding: 10,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Proyección: $' + Number(context.raw).toLocaleString('es-CO');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: textColor, font: { size: 11, weight: '600' } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        color: textColor,
                        font: { size: 10 },
                        callback: function(val) {
                            if (val >= 1000000) return (val/1000000) + 'M';
                            if (val >= 1000) return (val/1000) + 'k';
                            return val;
                        }
                    }
                }
            }
        }
    });

    // Interacción al tocar las microbarras del mes
    window.highlightMonth = function(label, ing, gast) {
        Swal.fire({
            title: `<span class="fw-bold">${label}</span>`,
            html: `
                <div class="d-flex flex-column gap-2 text-start p-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-success fw-bold"><i class="fa-solid fa-arrow-down-left me-1"></i> Ingresos:</span>
                        <span class="fw-bold text-success">$${Number(ing).toLocaleString('es-CO')}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-danger fw-bold"><i class="fa-solid fa-arrow-up-right me-1"></i> Gastos:</span>
                        <span class="fw-bold text-danger">$${Number(gast).toLocaleString('es-CO')}</span>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Balance:</span>
                        <span class="fw-bold ${ing >= gast ? 'text-success' : 'text-danger'}">$${Number(ing - gast).toLocaleString('es-CO')}</span>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-4 shadow-lg border-0 bg-body'
            }
        });
    };

    // Navegación de mes interactiva
    const months = ['Mayo de 2026', 'Junio de 2026', 'Julio de 2026', 'Agosto de 2026', 'Septiembre de 2026', 'Octubre de 2026'];
    let currentIdx = months.length - 1;
    const monthDisplay = document.getElementById('activeMonthDisplay');

    document.getElementById('btnPrevMonth')?.addEventListener('click', function() {
        if (currentIdx > 0) {
            currentIdx--;
            monthDisplay.innerText = months[currentIdx];
        }
    });

    document.getElementById('btnNextMonth')?.addEventListener('click', function() {
        if (currentIdx < months.length - 1) {
            currentIdx++;
            monthDisplay.innerText = months[currentIdx];
        }
    });
});
</script>

@endsection
