<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../public/index.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';

$titulo = 'Nuevo Pedido';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1400px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Nuevo Pedido</h3>
            <span class="text-muted small">Creación de pedidos de Purina</span>
        </div>
        <div>
            <a href="pedidos.php" class="btn btn-outline-secondary rounded-3 shadow-sm px-4 fw-medium">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver a Pedidos
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Panel Izquierdo: Búsqueda y Carrito -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Buscar Productos</h5>
                    <div class="position-relative mb-4">
                        <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                            <i class="fa-solid fa-search"></i>
                        </span>
                        <input type="text" id="buscar_producto" class="form-control form-control-lg bg-light border-0 rounded-3 ps-5" placeholder="Buscar por código de barras, código o nombre..." autocomplete="off">
                        <ul id="resultados_productos" class="list-group position-absolute w-100 shadow-sm mt-1" style="z-index: 1000; max-height: 250px; overflow-y: auto; display: none;">
                            <!-- Resultados AJAX -->
                        </ul>
                    </div>

                    <h5 class="fw-bold mb-3">Productos en el Pedido</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover" id="tabla_carrito">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th class="rounded-start">Producto</th>
                                    <th>Precio</th>
                                    <th>Stock Disp.</th>
                                    <th width="120">Cantidad</th>
                                    <th>Subtotal</th>
                                    <th class="rounded-end text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="carrito_body">
                                <tr id="carrito_vacio">
                                    <td colspan="6" class="text-center py-4 text-muted">No hay productos en el pedido.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Cliente, Pago y Entrega -->
        <div class="col-lg-4">
            <!-- Cliente -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Cliente</h5>
                    <div class="position-relative mb-2">
                        <span class="position-absolute top-50 translate-middle-y ms-3 text-muted">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" id="buscar_cliente" class="form-control bg-light border-0 rounded-3 ps-5" placeholder="Buscar cliente..." autocomplete="off">
                        <ul id="resultados_clientes" class="list-group position-absolute w-100 shadow-sm mt-1" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;">
                            <!-- Resultados AJAX -->
                        </ul>
                    </div>
                    
                    <div id="cliente_seleccionado_div" class="mt-3 p-3 bg-light rounded-3 d-none">
                        <input type="hidden" id="id_cliente_seleccionado" value="">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong id="nombre_cliente_lbl" class="d-block text-dark"></strong>
                                <small id="doc_cliente_lbl" class="text-muted"></small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger border-0" id="btn_quitar_cliente" title="Quitar">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen, Pago y Entrega -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(to bottom, #ffffff, #f8f9fa);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Detalles del Pedido</h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Método de Pago</label>
                        <select id="metodo_pago" class="form-select form-select-lg rounded-3 border-0 bg-white shadow-sm">
                            <option value="Pendiente">Pendiente</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Nequi">Nequi</option>
                            <option value="Daviplata">Daviplata</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia Bancaria</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Tipo de Entrega / Dirección</label>
                        <input type="text" id="entrega" class="form-control form-control-lg rounded-3 border-0 bg-white shadow-sm" placeholder="Ej: Calle 123 #45-67 o Local">
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-medium text-dark" id="lbl_subtotal">$0.00</span>
                    </div>
                    <hr class="border-light border-2 opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5 text-dark">Total:</span>
                        <span class="fw-bold fs-3 text-primary" id="lbl_total">$0.00</span>
                    </div>

                    <button id="btn_confirmar_pedido" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm fw-bold d-flex justify-content-center align-items-center gap-2" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                        <i class="fa-solid fa-check"></i> Generar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let carrito = [];
    
    const inputProducto = document.getElementById('buscar_producto');
    const resProductos = document.getElementById('resultados_productos');
    const tbodyCarrito = document.getElementById('carrito_body');
    
    const inputCliente = document.getElementById('buscar_cliente');
    const resClientes = document.getElementById('resultados_clientes');
    
    // --- LÓGICA DE PRODUCTOS ---
    inputProducto.addEventListener('input', function() {
        let q = this.value.trim();
        if (q.length < 2) {
            resProductos.style.display = 'none';
            return;
        }
        fetch(`../../controllers/PedidoController.php?action=search_product&q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                resProductos.innerHTML = '';
                if(data.length > 0) {
                    data.forEach(p => {
                        let li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer';
                        li.innerHTML = `
                            <div>
                                <strong>${p.nombre}</strong> <span class="badge bg-secondary ms-2">${p.codigo}</span>
                                <div class="small text-muted">Stock: ${p.stock_actual} | Precio: $${parseFloat(p.precio).toFixed(2)}</div>
                            </div>
                            <button class="btn btn-sm btn-primary rounded-circle"><i class="fa-solid fa-plus"></i></button>
                        `;
                        li.onclick = () => agregarAlCarrito(p);
                        resProductos.appendChild(li);
                    });
                    resProductos.style.display = 'block';
                } else {
                    resProductos.innerHTML = '<li class="list-group-item text-muted">No se encontraron productos</li>';
                    resProductos.style.display = 'block';
                }
            });
    });

    document.addEventListener('click', function(e) {
        if (e.target != inputProducto) resProductos.style.display = 'none';
        if (e.target != inputCliente) resClientes.style.display = 'none';
    });

    function agregarAlCarrito(prod) {
        if (parseFloat(prod.stock_actual) <= 0) {
            Swal.fire('Sin Stock', 'Este producto no tiene existencias disponibles.', 'warning');
            return;
        }

        let existe = carrito.find(p => p.id_producto == prod.id_producto);
        if (existe) {
            if (existe.cantidad < parseFloat(prod.stock_actual)) {
                existe.cantidad++;
                existe.subtotal = existe.cantidad * existe.precio;
            } else {
                Swal.fire('Stock Límite', 'No hay más unidades en inventario.', 'warning');
            }
        } else {
            carrito.push({
                id_producto: prod.id_producto,
                codigo: prod.codigo,
                nombre: prod.nombre,
                precio: parseFloat(prod.precio),
                stock_actual: parseFloat(prod.stock_actual),
                cantidad: 1,
                subtotal: parseFloat(prod.precio)
            });
        }
        inputProducto.value = '';
        inputProducto.focus();
        renderCarrito();
    }

    window.cambiarCantidad = function(id, delta) {
        let p = carrito.find(x => x.id_producto == id);
        if(!p) return;
        
        let n = p.cantidad + delta;
        if(n > p.stock_actual) {
            Swal.fire('Stock Límite', 'No hay suficientes unidades.', 'warning');
            return;
        }
        if(n < 1) return;
        
        p.cantidad = n;
        p.subtotal = p.cantidad * p.precio;
        renderCarrito();
    }

    window.quitarDelCarrito = function(id) {
        carrito = carrito.filter(x => x.id_producto != id);
        renderCarrito();
    }

    function renderCarrito() {
        if(carrito.length === 0) {
            tbodyCarrito.innerHTML = '<tr id="carrito_vacio"><td colspan="6" class="text-center py-4 text-muted">No hay productos en el pedido.</td></tr>';
        } else {
            tbodyCarrito.innerHTML = '';
            carrito.forEach(p => {
                tbodyCarrito.innerHTML += `
                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">${p.nombre}</span>
                                <span class="text-muted small">${p.codigo}</span>
                            </div>
                        </td>
                        <td class="text-muted">$${p.precio.toFixed(2)}</td>
                        <td class="text-muted">${p.stock_actual}</td>
                        <td>
                            <div class="input-group input-group-sm rounded-3" style="width: 100px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${p.id_producto}, -1)">-</button>
                                <input type="text" class="form-control text-center" value="${p.cantidad}" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${p.id_producto}, 1)">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">$${p.subtotal.toFixed(2)}</td>
                        <td class="text-end">
                            <button class="btn btn-light text-danger btn-sm rounded-circle shadow-sm cursor-pointer" onclick="quitarDelCarrito(${p.id_producto})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        calcularTotales();
    }

    function calcularTotales() {
        let subtotal = 0;
        carrito.forEach(p => { subtotal += p.subtotal; });
        
        document.getElementById('lbl_subtotal').innerText = '$' + subtotal.toFixed(2);
        document.getElementById('lbl_total').innerText = '$' + subtotal.toFixed(2);
    }

    // --- LÓGICA DE CLIENTES ---
    inputCliente.addEventListener('input', function() {
        let q = this.value.trim();
        if (q.length < 2) {
            resClientes.style.display = 'none';
            return;
        }
        fetch(`../../controllers/PedidoController.php?action=search_client&q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                resClientes.innerHTML = '';
                if(data.length > 0) {
                    data.forEach(c => {
                        let li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action cursor-pointer';
                        li.innerHTML = `<strong>${c.nombre}</strong> <small class="text-muted d-block">${c.numero_documento}</small>`;
                        li.onclick = () => seleccionarCliente(c);
                        resClientes.appendChild(li);
                    });
                    resClientes.style.display = 'block';
                } else {
                    resClientes.innerHTML = '<li class="list-group-item text-muted">No se encontraron clientes</li>';
                    resClientes.style.display = 'block';
                }
            });
    });

    function seleccionarCliente(c) {
        document.getElementById('id_cliente_seleccionado').value = c.id_cliente;
        document.getElementById('nombre_cliente_lbl').innerText = c.nombre;
        document.getElementById('doc_cliente_lbl').innerText = "Doc: " + c.numero_documento;
        
        inputCliente.value = '';
        inputCliente.classList.add('d-none');
        document.getElementById('cliente_seleccionado_div').classList.remove('d-none');
    }

    document.getElementById('btn_quitar_cliente').addEventListener('click', function() {
        document.getElementById('id_cliente_seleccionado').value = '';
        inputCliente.classList.remove('d-none');
        document.getElementById('cliente_seleccionado_div').classList.add('d-none');
    });

    // --- CONFIRMAR PEDIDO ---
    document.getElementById('btn_confirmar_pedido').addEventListener('click', function() {
        let id_cliente = document.getElementById('id_cliente_seleccionado').value;
        let metodo_pago = document.getElementById('metodo_pago').value;
        let entrega = document.getElementById('entrega').value.trim();
        
        if (!id_cliente) {
            Swal.fire('Falta Cliente', 'Por favor selecciona un cliente.', 'warning');
            return;
        }
        if (carrito.length === 0) {
            Swal.fire('Pedido Vacío', 'Agrega al menos un producto al pedido.', 'warning');
            return;
        }
        if (entrega === '') {
            Swal.fire('Falta Entrega', 'Por favor ingresa la dirección de entrega o indica si es local.', 'warning');
            return;
        }

        let subtotal = carrito.reduce((sum, p) => sum + p.subtotal, 0);

        let data = {
            id_cliente: id_cliente,
            metodo_pago: metodo_pago,
            entrega: entrega,
            subtotal: subtotal,
            total: subtotal,
            detalles: carrito
        };

        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generando...';

        fetch('../../controllers/PedidoController.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({...data, action: 'save_pedido'})
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status === 'success') {
                Swal.fire({
                    title: '¡Pedido Generado!',
                    text: 'El pedido se ha guardado correctamente.',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Ir a Pedidos',
                    cancelButtonText: 'Nuevo Pedido'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pedidos.php';
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('Error', resp.message, 'error');
                document.getElementById('btn_confirmar_pedido').disabled = false;
                document.getElementById('btn_confirmar_pedido').innerHTML = '<i class="fa-solid fa-check"></i> Generar Pedido';
            }
        })
        .catch(err => {
            Swal.fire('Error', 'Hubo un problema de conexión.', 'error');
            document.getElementById('btn_confirmar_pedido').disabled = false;
            document.getElementById('btn_confirmar_pedido').innerHTML = '<i class="fa-solid fa-check"></i> Generar Pedido';
        });
    });

});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
