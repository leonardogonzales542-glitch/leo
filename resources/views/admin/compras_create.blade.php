@extends('layouts.app')
@section('title', 'Nueva Compra')
@section('content')

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Registrar Compra (Entrada)</h3>
            <span class="text-muted small">Ingresa los productos que compraste a tu proveedor</span>
        </div>
        <div>
            <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary rounded-3 shadow-sm px-4 fw-medium">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 border-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('compras.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Datos de la Compra -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Datos del Proveedor</h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Proveedor</label>
                            <select name="id_proveedor" class="form-select bg-light rounded-3" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id_proveedor }}">{{ $prov->razon_social }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nº Documento / Factura</label>
                            <input type="text" name="numero_documento" class="form-control bg-light rounded-3" placeholder="Ej: F001-00045">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Observación</label>
                            <textarea name="observacion" class="form-control bg-light rounded-3" rows="3" placeholder="Notas adicionales"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de Productos -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Productos</h5>
                        
                        <!-- Formulario para agregar al detalle temporalmente -->
                        <div class="row g-2 mb-4 p-3 bg-light rounded-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Producto</label>
                                <select id="sel_producto" class="form-select">
                                    <option value="">Buscar producto...</option>
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id_producto }}" data-nombre="{{ $prod->nombre }}" data-precio="{{ $prod->precio_compra }}">{{ $prod->codigo }} - {{ $prod->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small fw-bold">Cantidad</label>
                                <input type="number" id="sel_cantidad" class="form-control" min="0.1" step="0.1" value="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">Precio Compra</label>
                                <input type="number" id="sel_precio" class="form-control" min="0" step="0.01">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" onclick="agregarProducto()" class="btn btn-success w-100 rounded-3">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de Detalles -->
                        <div class="table-responsive mb-4">
                            <table class="table align-middle" id="tabla_detalles">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Compra</th>
                                        <th>Subtotal</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row_empty">
                                        <td colspan="5" class="text-center text-muted py-4">No hay productos agregados.</td>
                                    </tr>
                                </tbody>
                                <tfoot style="display: none;" id="tfoot_total">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                        <td colspan="2" class="fw-bold text-success fs-5">$<span id="lbl_total">0.00</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="btn_guardar" class="btn btn-primary rounded-3 px-4 fw-medium shadow-sm" disabled>
                                <i class="fa-solid fa-save me-2"></i>Registrar Compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let detalle = [];
    let cont = 0;
    let total = 0;

    // Al seleccionar producto, autocompletar precio de compra
    document.getElementById('sel_producto').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            document.getElementById('sel_precio').value = option.getAttribute('data-precio');
        } else {
            document.getElementById('sel_precio').value = '';
        }
    });

    function agregarProducto() {
        const sel = document.getElementById('sel_producto');
        const id_producto = sel.value;
        const nombre = sel.options[sel.selectedIndex].getAttribute('data-nombre');
        const cantidad = parseFloat(document.getElementById('sel_cantidad').value);
        const precio = parseFloat(document.getElementById('sel_precio').value);

        if (!id_producto || cantidad <= 0 || isNaN(precio) || precio < 0) {
            alert('Por favor, ingresa los datos correctamente.');
            return;
        }

        // Verificar si ya existe
        const existe = detalle.find(d => d.id_producto === id_producto);
        if (existe) {
            alert('Este producto ya fue agregado al detalle.');
            return;
        }

        const subtotal = cantidad * precio;
        total += subtotal;

        detalle.push({
            id_producto: id_producto,
            nombre: nombre,
            cantidad: cantidad,
            precio: precio,
            subtotal: subtotal
        });

        const fila = `
            <tr id="fila${cont}">
                <td>
                    <input type="hidden" name="productos[${cont}][id_producto]" value="${id_producto}">
                    <input type="hidden" name="productos[${cont}][cantidad]" value="${cantidad}">
                    <input type="hidden" name="productos[${cont}][precio_compra]" value="${precio}">
                    ${nombre}
                </td>
                <td>${cantidad}</td>
                <td>$${precio.toFixed(2)}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-light text-danger" onclick="eliminarProducto(${cont}, ${subtotal}, '${id_producto}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        document.getElementById('row_empty').style.display = 'none';
        document.getElementById('tabla_detalles').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', fila);
        document.getElementById('tfoot_total').style.display = 'table-row-group';
        document.getElementById('lbl_total').innerText = total.toFixed(2);
        
        cont++;
        document.getElementById('btn_guardar').disabled = false;

        // Limpiar
        sel.value = '';
        document.getElementById('sel_cantidad').value = '1';
        document.getElementById('sel_precio').value = '';
    }

    function eliminarProducto(index, subtotal, id_producto) {
        document.getElementById('fila' + index).remove();
        total -= subtotal;
        document.getElementById('lbl_total').innerText = total.toFixed(2);
        
        detalle = detalle.filter(d => d.id_producto !== id_producto);

        if (detalle.length === 0) {
            document.getElementById('row_empty').style.display = 'table-row';
            document.getElementById('tfoot_total').style.display = 'none';
            document.getElementById('btn_guardar').disabled = true;
        }
    }
</script>
@endsection
