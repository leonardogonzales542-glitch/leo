<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 1) {
    header('Location: ../../views/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/devolucion.php';

$devolucionModel = new Devolucion($conn);
$devoluciones = $devolucionModel->getAll();

$modulo = "Devoluciones";
$titulo = 'Gestión de Devoluciones';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebaradmin.php';
?>

<div class="d-flex flex-column gap-4" style="max-width: 1200px; margin: 0 auto; width: 100%;">
    
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Devoluciones</h3>
            <span class="text-muted small">Historial de devoluciones de ventas y pedidos</span>
        </div>
        <div>
            <!-- Botón para nueva devolución usando modal -->
            <button class="btn btn-primary rounded-3 shadow-sm px-4 fw-medium" data-bs-toggle="modal" data-bs-target="#modalNuevaDevolucion" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); border: none;">
                <i class="fa-solid fa-plus me-2"></i>Nueva Devolución
            </button>
        </div>
    </div>

    <!-- Historial de Devoluciones -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tablaDevoluciones" class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="rounded-start">ID</th>
                            <th>Fecha</th>
                            <th>Origen</th>
                            <th>Motivo</th>
                            <th>Items Devueltos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($devoluciones && $devoluciones->num_rows > 0): ?>
                            <?php while($d = $devoluciones->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold text-primary">#<?= $d['id_devolucion'] ?></td>
                                    <td><?= date('d/m/Y h:i A', strtotime($d['fecha'])) ?></td>
                                    <td>
                                        <?php if ($d['id_venta']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3"><i class="fa-solid fa-store me-1"></i>Venta #<?= $d['id_venta'] ?></span>
                                        <?php elseif ($d['id_pedido']): ?>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3"><i class="fa-solid fa-truck me-1"></i>Pedido #<?= $d['id_pedido'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Desconocido</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?= htmlspecialchars($d['motivo']) ?></td>
                                    <td class="fw-bold text-dark"><?= intval($d['total_items']) ?> Unds</td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium"><?= htmlspecialchars($d['estado']) ?></span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Devolución -->
<div class="modal fade" id="modalNuevaDevolucion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Registrar Nueva Devolución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../controllers/DevolucionController.php" method="POST" id="formDevolucion">
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Origen de la Devolución</label>
                            <select name="origen" id="select_origen" class="form-select rounded-3 bg-light border-0" required>
                                <option value="" disabled selected>Seleccione el origen</option>
                                <option value="venta">Venta en tienda</option>
                                <option value="pedido">Pedido (Envío/Local)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">ID del Origen (N° Venta/Pedido)</label>
                            <input type="number" name="id_origen" class="form-control rounded-3 bg-light border-0" required min="1" placeholder="Ej. 123">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Motivo de la Devolución</label>
                        <textarea name="motivo" class="form-control rounded-3 bg-light border-0" rows="3" required placeholder="Escriba el motivo detallado de la devolución..."></textarea>
                    </div>
                    
                    <hr class="text-muted opacity-25">
                    <h6 class="fw-bold mb-3">Productos a Devolver</h6>
                    <div class="alert alert-info small border-0 py-2">
                        <i class="fa-solid fa-info-circle me-1"></i> Ingrese el ID del producto y la cantidad a devolver.
                    </div>
                    
                    <div id="productos_container">
                        <div class="row mb-2 producto-row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">ID Producto</label>
                                <input type="number" name="id_producto_temp[]" class="form-control rounded-3 bg-light border-0 id_producto_input" required min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Cantidad</label>
                                <input type="number" name="cantidad_temp[]" class="form-control rounded-3 bg-light border-0 cantidad_input" required min="0.01" step="0.01">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger rounded-3 w-100 btn-remove-producto" disabled>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-light text-primary rounded-3 btn-sm fw-bold" id="btn_add_producto">
                            <i class="fa-solid fa-plus me-1"></i> Agregar otro producto
                        </button>
                    </div>

                    <!-- Div oculto donde procesaremos los arrays productos[id_producto] = cantidad antes del submit -->
                    <div id="hidden_inputs_container"></div>

                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-medium shadow-sm">Registrar Devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tablaDevoluciones').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            order: [[0, 'desc']] 
        });
    }

    <?php if(isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['alert']['type'] ?>',
            title: '<?= addslashes($_SESSION['alert']['title']) ?>',
            text: '<?= addslashes($_SESSION['alert']['message']) ?>',
            timer: <?= $_SESSION['alert']['type'] == 'success' ? '3000' : 'null' ?>,
            showConfirmButton: <?= $_SESSION['alert']['type'] == 'success' ? 'false' : 'true' ?>
        });
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>
    
    // Lógica dinámica para agregar productos a la devolución
    const btnAddProducto = document.getElementById('btn_add_producto');
    const productosContainer = document.getElementById('productos_container');
    
    btnAddProducto.addEventListener('click', function() {
        const rows = document.querySelectorAll('.producto-row');
        const firstRow = rows[0];
        const newRow = firstRow.cloneNode(true);
        
        // Limpiar inputs
        const inputs = newRow.querySelectorAll('input');
        inputs.forEach(input => input.value = '');
        
        // Habilitar botón eliminar
        const btnRemove = newRow.querySelector('.btn-remove-producto');
        btnRemove.removeAttribute('disabled');
        btnRemove.addEventListener('click', function() {
            newRow.remove();
        });
        
        productosContainer.appendChild(newRow);
    });

    // Validar y preparar el form antes de enviarlo
    const formDevolucion = document.getElementById('formDevolucion');
    const hiddenInputsContainer = document.getElementById('hidden_inputs_container');
    
    formDevolucion.addEventListener('submit', function(e) {
        e.preventDefault();
        
        hiddenInputsContainer.innerHTML = ''; // Limpiar
        
        const rows = document.querySelectorAll('.producto-row');
        let valid = true;
        let idsSet = new Set();
        
        rows.forEach(row => {
            const idInput = row.querySelector('.id_producto_input');
            const qtyInput = row.querySelector('.cantidad_input');
            
            const idVal = idInput.value;
            const qtyVal = qtyInput.value;
            
            if (idVal && qtyVal) {
                if (idsSet.has(idVal)) {
                    valid = false;
                    Swal.fire('Error', 'El ID del producto ' + idVal + ' está repetido.', 'error');
                    return;
                }
                idsSet.add(idVal);
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `productos[${idVal}]`;
                hiddenInput.value = qtyVal;
                hiddenInputsContainer.appendChild(hiddenInput);
            }
        });
        
        if (valid) {
            // Remover los name de los inputs temporales para que no se envíen por POST
            document.querySelectorAll('.id_producto_input, .cantidad_input').forEach(el => el.removeAttribute('name'));
            this.submit();
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
