<?php
require_once "config/database.php";

// 1. Mover las imagenes generadas
$source_dir = 'C:\Users\Leonardo Gonzalez\.gemini\antigravity-ide\brain\72d53e7b-e8b3-4cf0-811f-57840a50a709\\';
$dest_dir = 'public/img/productos/';

$files = glob($source_dir . 'purina_*.jpg');
$imagenes_nuevas = [];

foreach ($files as $file) {
    $filename = basename($file);
    // El formato es purina_felix_1787081957370.jpg
    $parts = explode('_17', $filename);
    $new_filename = $parts[0] . '.jpg';
    
    if (copy($file, $dest_dir . $new_filename)) {
        $imagenes_nuevas[$parts[0]] = $new_filename;
    }
}

// 2. Obtener categoría
$id_cat = 1;
$res = $conn->query("SELECT id_categoria FROM categorias WHERE nombre = 'Alimento para Mascotas' LIMIT 1");
if ($res && $res->num_rows > 0) {
    $id_cat = $res->fetch_assoc()['id_categoria'];
}

// 3. Actualizar precios existentes
$updates = [
    'PUR-002' => 135000, // Dog Chow
    'PUR-003' => 125000, // Beneful
    'PUR-004' => 165000  // One SmartBlend
];

foreach ($updates as $cod => $precio) {
    $precio_compra = $precio * 0.8; 
    $conn->query("UPDATE productos SET precio_venta = $precio, precio_compra = $precio_compra WHERE codigo = '$cod'");
}

// 4. Insertar nuevos productos
$nuevos = [
    [
        'codigo' => 'PUR-005',
        'nombre' => 'Purina Felix Sobres (Caja x 12)',
        'desc' => 'Alimento húmedo para gatos adultos',
        'precio' => 35000,
        'img_key' => 'purina_felix'
    ],
    [
        'codigo' => 'PUR-006',
        'nombre' => 'Purina Cat Chow Adultos 8kg',
        'desc' => 'Alimento seco completo para gatos',
        'precio' => 95000,
        'img_key' => 'purina_cat_chow'
    ],
    [
        'codigo' => 'PUR-007',
        'nombre' => 'Purina Excellent Adulto 15kg',
        'desc' => 'Alimento ultra premium para perros',
        'precio' => 220000,
        'img_key' => 'purina_excellent'
    ],
    [
        'codigo' => 'PUR-008',
        'nombre' => 'Purina Pro Plan Cachorro 15kg',
        'desc' => 'Alimento premium para cachorros',
        'precio' => 285000,
        'img_key' => 'purina_pro_plan_cachorro'
    ]
];

foreach ($nuevos as $prod) {
    $img = isset($imagenes_nuevas[$prod['img_key']]) ? $imagenes_nuevas[$prod['img_key']] : 'default.png';
    $pc = $prod['precio'] * 0.8;
    $pv = $prod['precio'];
    
    // Check if exists
    $check = $conn->query("SELECT id_producto FROM productos WHERE codigo = '{$prod['codigo']}'");
    if ($check && $check->num_rows == 0) {
        $sql = "INSERT INTO productos (codigo, nombre, id_categoria, descripcion, unidad_medida, precio_compra, precio_venta, stock_actual, stock_minimo, imagen) 
                VALUES ('{$prod['codigo']}', '{$prod['nombre']}', $id_cat, '{$prod['desc']}', 'Unidad', $pc, $pv, 50, 10, '$img')";
        $conn->query($sql);
    }
}

echo "<html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body>";
echo "<script>
    Swal.fire('¡Actualizado!', 'Se han añadido nuevas purinas con imágenes y precios reales.', 'success')
    .then(() => { window.location.href = 'views/admin/inventario.php'; });
</script>";
echo "</body></html>";
?>
