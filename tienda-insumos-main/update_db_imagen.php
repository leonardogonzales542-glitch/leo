<?php
require_once "config/database.php";

$res_col = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen'");
if ($res_col->num_rows == 0) {
    $sql = "ALTER TABLE productos ADD COLUMN imagen VARCHAR(255) DEFAULT 'default.png'";
    $conn->query($sql);
}

$dir = __DIR__ . '/public/img/productos';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Rutas de origen (generadas por IA)
$src_pro_plan = 'C:/Users/Leonardo Gonzalez/.gemini/antigravity-ide/brain/6041cd40-149a-43dd-bd37-1cf6e00e284e/purina_pro_plan_1786738111642.png';
$src_dog_chow = 'C:/Users/Leonardo Gonzalez/.gemini/antigravity-ide/brain/6041cd40-149a-43dd-bd37-1cf6e00e284e/purina_dog_chow_1786738119611.png';
$src_beneful = 'C:/Users/Leonardo Gonzalez/.gemini/antigravity-ide/brain/6041cd40-149a-43dd-bd37-1cf6e00e284e/purina_beneful_1786738127318.png';
$src_one = 'C:/Users/Leonardo Gonzalez/.gemini/antigravity-ide/brain/6041cd40-149a-43dd-bd37-1cf6e00e284e/purina_one_1786738135113.png';

// Copiar archivos si existen
if(file_exists($src_pro_plan)) copy($src_pro_plan, $dir . '/purina_pro_plan.png');
if(file_exists($src_dog_chow)) copy($src_dog_chow, $dir . '/purina_dog_chow.png');
if(file_exists($src_beneful)) copy($src_beneful, $dir . '/purina_beneful.png');
if(file_exists($src_one)) copy($src_one, $dir . '/purina_one.png');

// Insertar categoría Purina si no existe
$conn->query("INSERT IGNORE INTO categorias (id_categoria, nombre, descripcion) VALUES (99, 'Alimento para Mascotas', 'Línea completa de Purina')");

// Insertar productos de prueba
$productos = [
    ['codigo' => 'PUR-001', 'nombre' => 'Purina Pro Plan Adulto', 'desc' => 'Alimento premium para perros adultos con alta actividad física.', 'precio' => 85.00, 'img' => 'purina_pro_plan.png'],
    ['codigo' => 'PUR-002', 'nombre' => 'Purina Dog Chow Adultos', 'desc' => 'Nutrición balanceada para una vida sana y feliz.', 'precio' => 45.00, 'img' => 'purina_dog_chow.png'],
    ['codigo' => 'PUR-003', 'nombre' => 'Purina Beneful Original', 'desc' => 'Ingredientes saludables que a tu perro le encantarán.', 'precio' => 50.00, 'img' => 'purina_beneful.png'],
    ['codigo' => 'PUR-004', 'nombre' => 'Purina ONE SmartBlend', 'desc' => 'Carnes reales como primer ingrediente.', 'precio' => 65.00, 'img' => 'purina_one.png']
];

foreach ($productos as $p) {
    // Comprobar si existe
    $res = $conn->query("SELECT id_producto FROM productos WHERE codigo = '{$p['codigo']}'");
    if ($res->num_rows == 0) {
        $conn->query("INSERT INTO productos (codigo, nombre, id_categoria, descripcion, unidad_medida, precio_compra, precio_venta, stock_actual, stock_minimo, estado, imagen) VALUES ('{$p['codigo']}', '{$p['nombre']}', 99, '{$p['desc']}', 'Saco 20Kg', {$p['precio']} - 10, {$p['precio']}, 50, 10, 1, '{$p['img']}')");
    } else {
        $conn->query("UPDATE productos SET imagen = '{$p['img']}' WHERE codigo = '{$p['codigo']}'");
    }
}

echo "<h1>Actualización Completada</h1>";
echo "<p>Columna 'imagen' verificada, imágenes copiadas y productos de ejemplo agregados.</p>";
?>
