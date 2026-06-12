<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Vista: Ventas y Facturación
 */

// 1. Datos Simulados de Ventas
$ventas = [
    [
        'id' => 'VTA-00104',
        'fecha' => 'Hoy, 11:30 AM',
        'cliente' => 'Veterinaria San Francisco',
        'productos' => '2x Dog Chow Adulto 15Kg, 1x Pro Plan Adulto 15Kg',
        'total' => 178000,
        'metodo' => 'Transferencia',
        'estado' => 'Completada'
    ],
    [
        'id' => 'VTA-00103',
        'fecha' => 'Hoy, 09:15 AM',
        'cliente' => 'María Clara Restrepo',
        'productos' => '1x Excellent Cachorro 3Kg',
        'total' => 22000,
        'metodo' => 'Efectivo',
        'estado' => 'Completada'
    ],
    [
        'id' => 'VTA-00102',
        'fecha' => 'Ayer, 05:40 PM',
        'cliente' => 'Pet Shop Huellitas',
        'productos' => '5x Pro Plan Puppy Large Breed 15Kg',
        'total' => 485000,
        'metodo' => 'Crédito',
        'estado' => 'Pendiente'
    ],
    [
        'id' => 'VTA-00101',
        'fecha' => '04 Jun 2026, 02:10 PM',
        'cliente' => 'Juan Carlos Pérez',
        'productos' => '1x Dog Chow Adulto 22.7Kg',
        'total' => 62000,
        'metodo' => 'Tarjeta Débito',
        'estado' => 'Completada'
    ],
    [
        'id' => 'VTA-00100',
        'fecha' => '03 Jun 2026, 10:05 AM',
        'cliente' => 'Distribuidora Canina',
        'productos' => '10x Dog Chow Cachorros 15Kg',
        'total' => 450000,
        'metodo' => 'Transferencia',
        'estado' => 'Cancelada'
    ]
];

$mensajeExito = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensajeExito = "¡Venta registrada con éxito en el sistema (Simulación)! El stock de los productos ha sido actualizado.";
    $nuevaVenta = [
        'id' => 'VTA-00' . (104 + count($ventas)),
        'fecha' => 'Hoy, ' . date('h:i A'),
        'cliente' => htmlspecialchars($_POST['cliente']),
        'productos' => htmlspecialchars($_POST['productos']),
        'total' => (double)$_POST['total'],
        'metodo' => htmlspecialchars($_POST['metodo']),
        'estado' => 'Completada'
    ];
    array_unshift($ventas, $nuevaVenta);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - PurinaStock</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center text-white" href="../index.php">
                <i class="bi bi-dog-fill text-warning me-2 fs-3"></i>
                <span class="fw-bold">Purina</span><span class="text-warning fw-light">Stock</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="../index.php">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="inventario.php">
                            <i class="bi bi-box-seam me-1"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="ventas.php">
                            <i class="bi bi-cart3 me-1"></i> Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="clientes.php">
                            <i class="bi bi-people me-1"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="reportes.php">
                            <i class="bi bi-graph-up-arrow me-1"></i> Reportes
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center text-white">
                    <div class="text-end me-3 d-none d-sm-block">
                        <span class="d-block fw-semibold text-white fs-7">Leonardo González</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.7rem;">Administrador</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&h=100&q=80" alt="avatar" width="40" height="40" class="rounded-circle border border-2 border-warning">
                </div>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="bg-white border-bottom py-3 mb-4">
        <div class="container-fluid px-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ventas</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Gestión de Ventas y Facturación</h3>
            </div>
            <div>
                <button class="btn btn-warning text-white fw-bold px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#newSaleModal">
                    <i class="bi bi-cart-plus-fill me-2"></i> Nueva Venta
                </button>
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="container-fluid px-5 pb-5">
        
        <?php if ($mensajeExito !== ""): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $mensajeExito; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Métricas Rápidas de Ventas -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary bg-opacity-10 text-primary border-start border-primary border-3">
                    <span class="text-uppercase small fw-bold">Facturación Semanal</span>
                    <h3 class="fw-bold mt-1 mb-0">$2,450,000</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-success bg-opacity-10 text-success border-start border-success border-3">
                    <span class="text-uppercase small fw-bold">Ventas Completadas</span>
                    <h3 class="fw-bold mt-1 mb-0"><?php echo count(array_filter($ventas, fn($v) => $v['estado'] === 'Completada')); ?> transacciones</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-warning bg-opacity-10 text-warning border-start border-warning border-3">
                    <span class="text-uppercase small fw-bold">Créditos Pendientes</span>
                    <h3 class="fw-bold mt-1 mb-0">$485,000</h3>
                </div>
            </div>
        </div>

        <!-- Tabla de Ventas -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Factura N°</th>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Productos Vendidos</th>
                                <th class="text-end">Monto Total</th>
                                <th class="text-center">Método Pago</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $v): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo $v['id']; ?></td>
                                <td class="text-muted" style="font-size: 0.9rem;"><i class="bi bi-calendar-event me-1"></i> <?php echo $v['fecha']; ?></td>
                                <td>
                                    <span class="fw-semibold text-dark"><?php echo $v['cliente']; ?></span>
                                </td>
                                <td class="text-muted text-truncate" style="max-width: 250px;">
                                    <?php echo $v['productos']; ?>
                                </td>
                                <td class="text-end fw-bold text-dark">$<?php echo number_format($v['total']); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border rounded-pill"><?php echo $v['metodo']; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($v['estado'] === 'Completada'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">Completada</span>
                                    <?php elseif ($v['estado'] === 'Pendiente'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">Pendiente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2">Cancelada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Ver Recibo"><i class="bi bi-file-earmark-pdf"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Anular"><i class="bi bi-x-circle"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL: NUEVA VENTA -->
    <div class="modal fade" id="newSaleModal" tabindex="-1" aria-labelledby="newSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                    <h5 class="modal-title fw-bold" id="newSaleModalLabel"><i class="bi bi-cart-plus me-2 text-warning"></i> Registrar Nueva Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="ventas.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente</label>
                            <input type="text" name="cliente" class="form-control" placeholder="Ej. Veterinaria San Francisco o Nombre de Cliente" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Productos y Cantidades</label>
                            <textarea name="productos" class="form-control" rows="3" placeholder="Ej. 2x Dog Chow Adulto 15Kg, 1x Pro Plan Puppy 3Kg" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Método de Pago</label>
                                <select name="metodo" class="form-select" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia">Transferencia Bancaria</option>
                                    <option value="Tarjeta Débito">Tarjeta Débito/Crédito</option>
                                    <option value="Crédito">Crédito Interno</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Monto Total ($)</label>
                                <input type="number" name="total" class="form-control" placeholder="Ej. 178000" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3 rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning text-white fw-bold rounded-pill px-4 shadow-sm">Registrar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white-50 py-4 mt-auto border-top border-secondary border-opacity-20">
        <div class="container-fluid px-5 text-center">
            <p class="mb-0">&copy; 2026 PurinaStock. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
