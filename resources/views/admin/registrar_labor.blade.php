@extends('layouts.app')
@section('title', 'Registrar Nuevo Gasto')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 900px; margin: 0 auto; width: 100%;">
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-2">
        <div class="card-body p-4 p-md-5 bg-white">
            <!-- NOTA: Debes actualizar la ruta a donde quieres que se guarde el gasto -->
            <form action="{{ route('egresos.store') }}" method="POST">
                @csrf
                
                <h5 class="fw-bold text-dark mb-4 border-bottom pb-3"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Detalles del Gasto Operativo</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Fecha del Gasto *</label>
                        <input type="date" name="fecha" class="form-control rounded-3" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Método de Pago *</label>
                        <select name="metodo_pago" class="form-select rounded-3" required>
                            <option value="">Selecciona un método...</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia (Nequi/Daviplata/Banco)</option>
                            <option value="Tarjeta">Tarjeta (Débito/Crédito)</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-muted small">Concepto / Descripción del Gasto *</label>
                        <input type="text" name="concepto" class="form-control rounded-3" placeholder="Ej: Pago de arriendo, Servicio de luz, Transporte, etc." required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Monto Original (Desglose)</label>
                        <input type="text" name="valor_original" id="valor_original" class="form-control rounded-3" placeholder="Ej: 30000 + 15000">
                        <div class="form-text mt-1 text-muted"><i class="fa-solid fa-circle-info me-1"></i>Puedes escribir sumas para calcular automáticamente el total.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Total a Pagar ($) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-danger fw-bold">$</span>
                            <input type="text" name="monto_display" id="monto_display" class="form-control bg-white border-start-0 ps-0 rounded-end-3 text-dark fw-bold" value="0.00" readonly tabindex="-1">
                        </div>
                        <input type="hidden" name="monto" id="monto" value="0">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end border-top pt-4 mt-4">
                    <button type="submit" class="btn btn-success rounded-3 px-4 py-2 fw-medium shadow-sm">
                        <i class="fa-solid fa-save me-2"></i>Registrar Gasto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputOriginal = document.getElementById('valor_original');
    const inputDisplay = document.getElementById('monto_display');
    const inputHidden = document.getElementById('monto');

    function calcularTotal(texto) {
        if (!texto || texto.trim() === '') return 0;
        let expresionLimpia = texto.replace(/\./g, '');
        expresionLimpia = expresionLimpia.replace(/[^0-9+\-*/\(\)\s]/g, '');
        try {
            let resultado = Function('"use strict";return (' + expresionLimpia + ')')();
            if (isNaN(resultado) || !isFinite(resultado)) return 0;
            return resultado;
        } catch (e) {
            return 0;
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('es-CO', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        }).format(num);
    }

    if (inputOriginal) {
        inputOriginal.addEventListener('input', function() {
            let valor = this.value;
            let total = calcularTotal(valor);
            inputDisplay.value = formatNumber(total);
            inputHidden.value = total;
        });
    }
});
</script>

@endsection
