<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// Ensure standard error reporting doesn't break JSON output
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get_productos_recomendados') {
        
        // Keywords to match dog related products
        $keywords = ['purina', 'alimento'];
        
        if (isset($_GET['kw']) && !empty($_GET['kw'])) {
            // Priority keyword from JS logic (e.g., puppy, peso, purina)
            $keywords = [$_GET['kw'], 'purina'];
        } else {
            $keywords = ['purina', 'alimento', 'snack', 'dog chow', 'pro plan'];
        }
        
        $like_conditions = [];
        foreach ($keywords as $kw) {
            $like_conditions[] = "nombre LIKE '%" . $conn->real_escape_string($kw) . "%' OR descripcion LIKE '%" . $conn->real_escape_string($kw) . "%'";
        }
        
        // Query to get matching active products, randomize to show different recommendations each time
        $sql = "SELECT id_producto, nombre, descripcion, precio_venta, imagen FROM productos 
                WHERE estado = 1 AND (" . implode(' OR ', $like_conditions) . ")
                ORDER BY RAND() LIMIT 8";
                
        $result = $conn->query($sql);
        $productos = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Build correct image path relative to the views/cliente folder
                if (empty($row['imagen']) || $row['imagen'] === 'default.png') {
                    $row['imagen_url'] = '../../public/img/default.png'; // Fallback
                } else {
                    $row['imagen_url'] = '../../public/img/' . $row['imagen'];
                }
                $productos[] = $row;
            }
        } else {
            // Fallback: If no specific dog products found, just return any 4 random active products
            $sql_fallback = "SELECT id_producto, nombre, descripcion, precio_venta, imagen FROM productos WHERE estado = 1 ORDER BY RAND() LIMIT 4";
            $result_fallback = $conn->query($sql_fallback);
            if ($result_fallback) {
                while ($row = $result_fallback->fetch_assoc()) {
                    if (empty($row['imagen']) || $row['imagen'] === 'default.png') {
                        $row['imagen_url'] = '../../public/img/default.png';
                    } else {
                        $row['imagen_url'] = '../../public/img/' . $row['imagen'];
                    }
                    $productos[] = $row;
                }
            }
        }
        
        echo json_encode(['success' => true, 'data' => $productos]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Acción no válida o no especificada']);
exit;
?>
