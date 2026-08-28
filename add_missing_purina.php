<?php
require_once 'config/database.php';

// Nuevos productos a agregar
$nuevos_productos = [
    [
        'codigo' => 'PUR-009',
        'nombre' => 'Purina Fancy Feast',
        'descripcion' => 'Alimento húmedo premium para gatos',
        'precio_compra' => 30000,
        'precio_venta' => 45000,
        'imagen' => 'purina_fancy_feast.jpg',
        'source_img' => 'C:\Users\Leonardo Gonzalez\.gemini\antigravity-ide\brain\9e2aa421-7a03-4cf4-8341-5067115a0a21\purina_fancy_feast_1787084716033.jpg'
    ],
    [
        'codigo' => 'PUR-010',
        'nombre' => 'Purina Friskies',
        'descripcion' => 'Alimento seco para gatos adultos',
        'precio_compra' => 60000,
        'precio_venta' => 85000,
        'imagen' => 'purina_friskies.jpg',
        'source_img' => 'public/img/productos/purina_felix.jpg' // Placeholder as fallback due to quota
    ],
    [
        'codigo' => 'PUR-011',
        'nombre' => 'Purina Alpo',
        'descripcion' => 'Alimento seco para perros adultos con carne',
        'precio_compra' => 85000,
        'precio_venta' => 115000,
        'imagen' => 'purina_alpo.png',
        'source_img' => 'public/img/productos/purina_dog_chow.png' // Placeholder as fallback
    ],
    [
        'codigo' => 'PUR-012',
        'nombre' => 'Purina Puppy Chow',
        'descripcion' => 'Alimento para cachorros',
        'precio_compra' => 95000,
        'precio_venta' => 130000,
        'imagen' => 'purina_puppy_chow.jpg',
        'source_img' => 'public/img/productos/purina_pro_plan_cachorro.jpg' // Placeholder as fallback
    ]
];

// Obtener id_categoria para "Alimento para Mascotas" o similar
$stmt = $conn->prepare("SELECT id_categoria FROM categorias LIMIT 1"); // Asumiendo que la categoría principal está en la DB
$stmt->execute();
$result = $stmt->get_result();
$id_categoria = 1; // Default
if ($row = $result->fetch_assoc()) {
    $id_categoria = $row['id_categoria'];
}

$success_count = 0;

foreach ($nuevos_productos as $prod) {
    // Verificar si el código ya existe
    $stmt = $conn->prepare("SELECT id_producto FROM productos WHERE codigo = ?");
    $stmt->bind_param("s", $prod['codigo']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo "El producto " . $prod['codigo'] . " ya existe.<br>";
        continue;
    }
    
    // Copiar la imagen
    $dest_path = 'public/img/productos/' . $prod['imagen'];
    if (file_exists($prod['source_img'])) {
        copy($prod['source_img'], $dest_path);
    } else {
        echo "No se encontro la imagen fuente para: " . $prod['nombre'] . "<br>";
    }

    // Insertar en la BD
    $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre, id_categoria, descripcion, unidad_medida, precio_compra, precio_venta, stock_actual, stock_minimo, estado, imagen) VALUES (?, ?, ?, ?, 'Unidad', ?, ?, 50, 10, 1, ?)");
    $stmt->bind_param("ssisddds", $prod['codigo'], $prod['nombre'], $id_categoria, $prod['descripcion'], $prod['precio_compra'], $prod['precio_venta'], $prod['imagen']);
    
    if ($stmt->execute()) {
        echo "Producto agregado: " . $prod['nombre'] . "<br>";
        $success_count++;
    } else {
        echo "Error al agregar: " . $prod['nombre'] . " - " . $stmt->error . "<br>";
    }
}

echo "Proceso completado. $success_count productos nuevos agregados.";
?>
