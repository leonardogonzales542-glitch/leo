@extends('layouts.app')
@section('title', $titulo)
@section('content')

<style>
/* ───── Thermal POS Ticket styles ───── */
.ticket-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px 0 40px;
    background-color: #f0f2f5;
}

.ticket {
    width: 320px;
    background: #fff;
    font-family: 'Consolas', 'Courier New', monospace;
    font-size: 13px;
    color: #000;
    padding: 20px 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.ticket div, .ticket span, .ticket td, .ticket th {
    font-family: 'Consolas', 'Courier New', monospace;
    color: #000;
}

.ticket-title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

.ticket-divider {
    border: none;
    border-top: 1px solid #000;
    margin: 8px 0;
}

/* Flex rows */
.flex-row-between {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.text-right {
    text-align: right;
}
.text-center {
    text-align: center;
}
.font-bold {
    font-weight: bold;
}

/* Items */
.item-block {
    margin-bottom: 10px;
    line-height: 1.2;
}

.totals-table {
    width: 100%;
    margin-top: 5px;
}
.totals-table td {
    padding: 2px 0;
}
.totals-table .val {
    text-align: right;
}
.total-final {
    font-size: 16px;
    font-weight: bold;
}

.payment-table {
    width: 100%;
    margin-top: 5px;
    border-collapse: collapse;
}
.payment-table th {
    text-align: left;
    font-weight: normal;
    border-bottom: 1px solid #000;
    padding-bottom: 2px;
    margin-bottom: 2px;
}
.payment-table td {
    padding-top: 4px;
}

.qr-container {
    text-align: center;
    margin-top: 15px;
}
.qr-container img {
    width: 150px;
    height: 150px;
    margin-top: 5px;
}

/* ───── Print ───── */
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area {
        position: absolute;
        left: 0; top: 0;
        display: flex;
        justify-content: flex-start;
        width: 100%;
    }
    .ticket-wrapper {
        background: transparent;
        padding: 0;
    }
    .no-print { display: none !important; }
    .ticket {
        box-shadow: none;
        width: 100%;
        max-width: 80mm;
        padding: 0;
        margin: 0;
    }
    @page {
        size: 80mm auto;
        margin: 0;
    }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print" style="max-width: 900px; margin: 0 auto;">
    <div>
        <h3 class="fw-bold text-dark mb-1">Factura de Venta</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">
            <i class="fa-solid fa-arrow-left me-2"></i>Volver
        </a>
        <button onclick="window.print()" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium">
            <i class="fa-solid fa-print me-2"></i>Imprimir
        </button>
    </div>
</div>

<div class="ticket-wrapper print-area">
    <div class="ticket">

        <div class="ticket-title">FACTURA DE VENTA</div>
        
        <div class="flex-row-between" style="margin-bottom: 5px;">
            <div>Fecha {{ date('d/m/y', strtotime($venta['fecha'])) }}</div>
            <div class="text-right">
                Nro. {{ $venta['numero_factura'] }}<br>
                Hora {{ date('H:i:s', strtotime($venta['fecha'])) }}
            </div>
        </div>

        <hr class="ticket-divider">

        <div style="line-height: 1.3;">
            <div>Cliente: {{ str_pad($venta['id_cliente'] ?? '0', 10, '0', STR_PAD_LEFT) }}</div>
            <div>Nombre: {{ $venta['cliente'] }}</div>
            @if(!empty($venta['numero_documento']))
                <div>DNI Nro.: {{ $venta['numero_documento'] }}</div>
            @else
                <div>DNI Nro.: 00000000</div>
            @endif
            <div>A Consumidor Final</div>
        </div>

        <hr class="ticket-divider">

        @foreach($venta['detalles'] as $item)
            <div class="item-block">
                <div>{{ number_format($item['cantidad'], 4, '.', '') }} x {{ number_format($item['precio_unitario'], 4, '.', ',') }}</div>
                <div>ARTICULO: {{ str_pad($item['id_producto'], 8, '0', STR_PAD_LEFT) }}</div>
                <div class="flex-row-between">
                    <span style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['nombre'] }}</span>
                    <span>{{ number_format($item['cantidad'] * $item['precio_unitario'], 2, '.', ',') }}</span>
                </div>
            </div>
        @endforeach

        <hr class="ticket-divider">

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="val">{{ number_format($venta['subtotal'], 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Descuentos:</td>
                <td class="val">{{ $venta['descuento'] > 0 ? number_format($venta['descuento'], 2, '.', ',') : '' }}</td>
            </tr>
            <tr>
                <td>Recargos:</td>
                <td class="val"></td>
            </tr>
            <tr>
                <td class="total-final">TOTAL:</td>
                <td class="val total-final">{{ number_format($venta['total'], 2, '.', ',') }}</td>
            </tr>
        </table>

        <hr class="ticket-divider">

        <table class="payment-table">
            <tr>
                <th style="width: 70%;">Valor</th>
                <th class="text-right" style="width: 30%;">Monto</th>
            </tr>
            <tr>
                <td>PESOS</td>
                <td class="text-right">{{ number_format($venta['total'], 2, '.', ',') }}</td>
            </tr>
        </table>

        <div class="qr-container">
            <div>CAE: {{ rand(100000000, 999999999) }}{{ rand(10000, 99999) }}</div>
            <div>VTO CAE: {{ date('d/m/y', strtotime('+10 days', strtotime($venta['fecha']))) }}</div>
            
            <div id="qrcode" style="display: flex; justify-content: center; margin-top: 8px;"></div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new QRCode(document.getElementById("qrcode"), {
            text: "Fac:{{ $venta['numero_factura'] }}|Total:{{ $venta['total'] }}",
            width: 130,
            height: 130,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.L
        });
    });
</script>

@endsection
