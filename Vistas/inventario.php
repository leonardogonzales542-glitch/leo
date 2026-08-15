<?php
/**
 * PurinaStock - Sistema de Control de Inventario y Ventas
 * Vista: Inventario de Productos
 */

// 1. Datos Simulados de Productos
$productos = [
    [
        'codigo' => 'PUR-DOG-ADU-15',
        'marca' => 'Dog Chow',
        'nombre' => 'Adultos Medianos y Grandes',
        'categoria' => 'Adulto',
        'peso' => '15 Kg',
        'stock' => 3,
        'stock_min' => 10,
        'precio_compra' => 32000,
        'precio_venta' => 45000,
    ],
    [
        'codigo' => 'PUR-PRO-PUP-03',
        'marca' => 'Pro Plan',
        'nombre' => 'Puppy Razas Pequeñas',
        'categoria' => 'Cachorro',
        'peso' => '3 Kg',
        'stock' => 0,
        'stock_min' => 5,
        'precio_compra' => 12500,
        'precio_venta' => 18000,
    ],
    [
        'codigo' => 'PUR-EXC-SEN-12',
        'marca' => 'Excellent',
        'nombre' => 'Adulto Sensitive - Cuidado Especial',
        'categoria' => 'Adulto / Cuidado Especial',
        'peso' => '12 Kg',
        'stock' => 4,
        'stock_min' => 8,
        'precio_compra' => 27000,
        'precio_venta' => 38000,
    ],
    [
        'codigo' => 'PUR-DOG-CACH-22',
        'marca' => 'Dog Chow',
        'nombre' => 'Cachorros Minis y Pequeños',
        'categoria' => 'Cachorro',
        'peso' => '22.7 Kg',
        'stock' => 2,
        'stock_min' => 6,
        'precio_compra' => 44000,
        'precio_venta' => 62000,
    ],
    [
        'codigo' => 'PUR-PRO-ADU-15',
        'marca' => 'Pro Plan',
        'nombre' => 'Adulto Mediano y Grande OptiHealth',
        'categoria' => 'Adulto',
        'peso' => '15 Kg',
        'stock' => 24,
        'stock_min' => 8,
        'precio_compra' => 52000,
        'precio_venta' => 75000,
    ],
    [
        'codigo' => 'PUR-CAT-ADU-08',
        'marca' => 'Cat Chow',
        'nombre' => 'Gatos Adultos Delicias de Pescado',
        'categoria' => 'Felinos (Otros)',
        'peso' => '8 Kg',
        'stock' => 15,
        'stock_min' => 5,
        'precio_compra' => 18000,
        'precio_venta' => 26000,
    ]
];

// Procesamiento de formulario simulado para agregar producto
$mensajeExito = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensajeExito = "¡Producto agregado con éxito al inventario (Simulación)!";
    // Agregamos el producto de forma dinámica temporal
    $nuevoProducto = [
        'codigo' => htmlspecialchars($_POST['codigo']),
        'marca' => htmlspecialchars($_POST['marca']),
        'nombre' => htmlspecialchars($_POST['nombre']),
        'categoria' => htmlspecialchars($_POST['categoria']),
        'peso' => htmlspecialchars($_POST['peso']) . ' Kg',
        'stock' => (int)$_POST['stock'],
        'stock_min' => (int)$_POST['stock_min'],
        'precio_compra' => (double)$_POST['precio_compra'],
        'precio_venta' => (double)$_POST['precio_venta'],
    ];
    array_unshift($productos, $nuevoProducto);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - PurinaStock</title>
    
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
                        <a class="nav-link nav-link-custom active" href="inventario.php">
                            <i class="bi bi-box-seam me-1"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="ventas.php">
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
                        <li class="breadcrumb-item active" aria-current="page">Inventario</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Control de Productos y Alimentos</h3>
            </div>
            <div>
                <button class="btn btn-warning text-white fw-bold px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#newProductModal">
                    <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Producto
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

        <!-- Filtros y Búsqueda -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control bg-light border-0" placeholder="Buscar por marca, código o descripción...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select bg-light border-0">
                            <option value="">Todas las Categorías</option>
                            <option value="Cachorro">Cachorro</option>
                            <option value="Adulto">Adulto</option>
                            <option value="Especial">Cuidado Especial</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select bg-light border-0">
                            <option value="">Todas las Marcas</option>
                            <option value="Dog Chow">Dog Chow</option>
                            <option value="Pro Plan">Pro Plan</option>
                            <option value="Excellent">Excellent</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-md-end">
                        <button class="btn btn-outline-secondary w-100 rounded-pill"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th class="text-center">Peso</th>
                                <th class="text-end">Costo</th>
                                <th class="text-end">P. Venta</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $p): ?>
                            <tr>
                                <td class="fw-semibold text-muted" style="font-size: 0.85rem;"><?php echo $p['codigo']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary rounded-3 p-2 me-3 fs-5">
                                            <i class="bi bi-box-seam-fill text-warning"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold mb-0 text-dark"><?php echo $p['marca']; ?></span>
                                            <span class="text-muted small"><?php echo $p['nombre']; ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border rounded-pill"><?php echo $p['categoria']; ?></span></td>
                                <td class="text-center fw-semibold"><?php echo $p['peso']; ?></td>
                                <td class="text-end fw-semibold text-muted">$<?php echo number_format($p['precio_compra']); ?></td>
                                <td class="text-end fw-bold text-success">$<?php echo number_format($p['precio_venta']); ?></td>
                                <td class="text-center">
                                    <span class="fw-bold <?php echo ($p['stock'] <= $p['stock_min']) ? 'text-danger' : 'text-dark'; ?>">
                                        <?php echo $p['stock']; ?>
                                    </span>
                                    <span class="text-muted small"> / <?php echo $p['stock_min']; ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['stock'] === 0): ?>
                                        <span class="badge rounded-pill badge-out-of-stock px-3 py-2">Sin Stock</span>
                                    <?php elseif ($p['stock'] <= $p['stock_min']): ?>
                                        <span class="badge rounded-pill badge-low-stock px-3 py-2">Stock Bajo</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill badge-in-stock px-3 py-2">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Editar"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Agregar Stock"><i class="bi bi-plus-lg"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL: NUEVO PRODUCTO -->
    <div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                    <h5 class="modal-title fw-bold" id="newProductModalLabel"><i class="bi bi-box-seam me-2 text-warning"></i> Registrar Nuevo Alimento / Purina</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="inventario.php" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Código SKU</label>
                                <input type="text" name="codigo" class="form-control" placeholder="Ej. PUR-PRO-ADU-15" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Marca</label>
                                <select name="marca" class="form-select" required>
                                    <option value="Dog Chow">Dog Chow</option>
                                    <option value="Pro Plan">Pro Plan</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Cat Chow">Cat Chow</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Nombre del Producto / Descripción</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej. Adulto Razas Medianas sabor Pollo" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Peso Bolsa (Kg)</label>
                                <input type="text" name="peso" class="form-control" placeholder="Ej. 15" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Categoría</label>
                                <input type="text" name="categoria" class="form-control" placeholder="Ej. Cachorro, Adulto, Felino" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Stock Inicial</label>
                                <input type="number" name="stock" class="form-control" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Stock Mínimo</label>
                                <input type="number" name="stock_min" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Precio Compra (Costo)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio_compra" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Precio Venta al Público</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="precio_venta" class="form-control" min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3 rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning text-white fw-bold rounded-pill px-4 shadow-sm">Guardar Producto</button>
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
