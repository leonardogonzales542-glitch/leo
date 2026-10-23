@extends('layouts.app')
@section('title', 'Gestión de Ventas')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Ventas</h3>
            <span class="text-muted small">Panel principal y estadisticas de ventas</span>
        </div>
            <a href="{{ route('ventas.create') }}" class="btn btn-primary rounded-3 shadow-sm px-3 py-2 fw-semibold" style="background-color: #2563eb; border-color: #2563eb; font-size: 0.9rem;">
                <i class="fa-solid fa-plus me-1"></i> Nueva venta
            </a>
    </div>

    <!-- Panel de Estadísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid var(--primary) !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Ventas de Hoy</h6>
                    <h3 class="fw-bold text-dark mb-0">${{ number_format($stats['ventas_hoy'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Ventas del Mes</h6>
                    <h3 class="fw-bold text-dark mb-0">${{ number_format($stats['ventas_mes'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Productos Vendidos</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ number_format($stats['productos_vendidos'], 0) }} Unds</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">Total Fiado (Por cobrar)</h6>
                    <h3 class="fw-bold text-danger mb-0">${{ number_format($stats['total_fiado'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Ventas -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tablaVentas" class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="rounded-start">Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Método</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-center rounded-end" style="width: 110px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($ventas && $ventas->count() > 0)
                            @foreach($ventas as $v)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $v->numero_factura ?? '' }}</td>
                                    <td>{{ date('d/m/Y h:i A', strtotime($v->fecha)) }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $v->cliente ?? 'Cliente No Encontrado' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $v->metodo_pago ?? 'N/A' }}</span>
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($v->total, 2) }}</td>
                                    <td>
                                        @if($v->estado == 'ACTIVA')
                                            <span class="badge-estado-activo">Activa</span>
                                        @else
                                            <span class="badge-estado-inactivo">Anulada</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="width: 110px;">
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <a href="{{ route('ventas.factura', ['id' => $v->id_venta]) }}" 
                                               class="btn-action-clean text-primary" 
                                               title="Ver Factura">
                                                <i class="fa-solid fa-file-invoice" style="color: #3b82f6; font-size: 1.05rem;"></i>
                                            </a>
                                            @if($v->estado == 'ACTIVA')
                                            <button type="button" 
                                                onclick="confirmarAnulacion({{ $v->id_venta }})" 
                                                class="btn-action-clean text-danger" 
                                                title="Anular Venta">
                                                <i class="fa-solid fa-ban" style="color: #ef4444; font-size: 1.05rem;"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay ventas registradas.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tablaVentas').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[1, 'desc']] // Ordenar por fecha descendente
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif
});

function confirmarAnulacion(id) {
    Swal.fire({
        title: '¿Anular esta venta?',
        text: "La venta quedará anulada y los productos regresarán al inventario.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, anular venta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/ventas/' + id;
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    })
}
</script>

@endsection
