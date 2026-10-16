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
    font-family: 'Arial', sans-serif;
    font-size: 11px; /* Letra pequeña de ticket */
    color: #000;
    padding: 20px 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.ticket div, .ticket span, .ticket td, .ticket th {
    font-family: 'Arial', sans-serif;
    color: #000;
    line-height: 1.2;
}

.ticket-title {
    text-align: center;
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 5px;
}

.ticket-divider {
    border: none;
    border-top: 1px solid #000;
    margin: 6px 0;
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
    margin-bottom: 8px;
}

.totals-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-top: 5px;
    padding-right: 5px;
}

.totals-table {
    width: 65%;
}
.totals-table td {
    padding: 1px 0;
}
.totals-table .val {
    text-align: right;
}
.total-final {
    font-size: 14px;
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
    margin-top: 20px;
}
.qr-container #qrcode {
    margin-top: 5px;
    display: flex;
    justify-content: center;
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
        <h3 class="fw-bold text-dark mb-1">Recibo de Pedido</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary rounded-3 px-4 fw-medium">
            <i class="fa-solid fa-arrow-left me-2"></i>Volver
        </a>
        <button id="downloadPdfBtn" class="btn btn-danger rounded-3 shadow-sm px-4 fw-medium">
            <i class="fa-solid fa-file-pdf me-2"></i>PDF
        </button>
        <button onclick="window.print()" class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium">
            <i class="fa-solid fa-print me-2"></i>Imprimir
        </button>
    </div>
</div>

<div class="ticket-wrapper print-area">
    <div class="ticket">

        <div class="ticket-title">PEDIDO</div>
        <div class="text-center" style="margin-bottom: 8px;">
            AgriStock<br>Comprobante de Pedido
        </div>
        
        <div class="flex-row-between" style="margin-bottom: 5px;">
            <div>Fecha {{ date('d/m/y', strtotime($pedido['fecha'])) }}</div>
            <div class="text-right">
                Nro. {{ str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) }}<br>
                Hora {{ date('H:i:s', strtotime($pedido['fecha'])) }}
            </div>
        </div>

        <hr class="ticket-divider">

        <div>
            <div>Cliente: {{ $pedido['cliente'] ?? 'Consumidor Final' }}</div>
            @if(!empty($pedido['cliente_documento']))
                <div>DNI Nro.: {{ $pedido['cliente_documento'] }}</div>
            @else
                <div>DNI Nro.: 00000000</div>
            @endif
            @if(!empty($pedido['entrega']))
                <div>Entrega: {{ $pedido['entrega'] }}</div>
            @endif
            @if(!empty($pedido['metodo_pago']))
                <div>Método Pago: {{ $pedido['metodo_pago'] }}</div>
            @endif
        </div>

        <hr class="ticket-divider">

        @foreach($pedido['detalles'] as $item)
            <div class="item-block">
                <div>{{ number_format($item['cantidad'], 4, '.', '') }} x {{ number_format($item['precio_unitario'], 4, '.', ',') }}</div>
                <div>ARTICULO: {{ str_pad($item['id_producto'], 8, '0', STR_PAD_LEFT) }}</div>
                <div class="flex-row-between">
                    <span style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['producto_nombre'] ?? 'Desconocido' }}</span>
                    <span>{{ number_format($item['subtotal'], 2, '.', ',') }}</span>
                </div>
            </div>
        @endforeach

        <hr class="ticket-divider">

        <div class="totals-wrapper">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="val">{{ number_format($pedido['subtotal'], 2, '.', ',') }}</td>
                </tr>
                @if($pedido['total'] > $pedido['subtotal'])
                <tr>
                    <td>Recargos/Envío:</td>
                    <td class="val">{{ number_format($pedido['total'] - $pedido['subtotal'], 2, '.', ',') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="total-final">TOTAL:</td>
                    <td class="val total-final">{{ number_format($pedido['total'], 2, '.', ',') }}</td>
                </tr>
            </table>
        </div>

        <hr class="ticket-divider">

        <table class="payment-table">
            <tr>
                <th style="width: 20%;">Valor</th>
                <th style="width: 50%;"></th>
                <th class="text-right" style="width: 30%;">Monto</th>
            </tr>
            <tr>
                <td>0</td>
                <td>PESOS</td>
                <td class="text-right">{{ number_format($pedido['total'], 2, '.', ',') }}</td>
            </tr>
        </table>

        <div class="qr-container">
            <div>CAE: {{ rand(100000000, 999999999) }}{{ rand(10000, 99999) }}</div>
            <div>VTO CAE: {{ date('d/m/y', strtotime('+10 days', strtotime($pedido['fecha']))) }}</div>
            
            <div id="qrcode"></div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new QRCode(document.getElementById("qrcode"), {
            text: "Pedido:{{ $pedido['id_pedido'] }}|Total:{{ $pedido['total'] }}",
            width: 220, /* QR más grande según la imagen */
            height: 220,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.L
        });

        // Configuración para descargar PDF
        document.getElementById('downloadPdfBtn').addEventListener('click', function () {
            const btn = this;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Generando...';
            btn.disabled = true;

            const element = document.querySelector('.ticket');
            
            const opt = {
                margin:       3,
                filename:     'Recibo_Pedido_{{ $pedido["id_pedido"] }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: [80, 250], orientation: 'portrait' }
            };
            
            html2pdf().set(opt).from(element).save().then(function() {
                btn.innerHTML = '<i class="fa-solid fa-file-pdf me-2"></i>PDF';
                btn.disabled = false;
            });
        });
    });
</script>

@endsection
