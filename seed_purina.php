<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$catId = DB::table('categorias')->insertGetId([
    'nombre' => 'Alimento para Perros', 
    'descripcion' => 'Concentrados y purinas para perros', 
    'estado' => 1
]);

$productos = [
    ['codigo' => 'PUR-001', 'nombre' => 'Purina Dog Chow Adultos 22kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 80000, 'precio_venta' => 110000, 'stock_actual' => 15, 'stock_minimo' => 5, 'estado' => 1, 'imagen' => 'purina1.jpg'],
    ['codigo' => 'PUR-002', 'nombre' => 'Purina Pro Plan Cachorros 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 180000, 'precio_venta' => 230000, 'stock_actual' => 8, 'stock_minimo' => 3, 'estado' => 1, 'imagen' => 'purina2.jpg'],
    ['codigo' => 'PUR-003', 'nombre' => 'Pedigree Adulto Nutrición Completa 20kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 95000, 'precio_venta' => 125000, 'stock_actual' => 20, 'stock_minimo' => 5, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-004', 'nombre' => 'Agility Gold Grandes Adultos 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 160000, 'precio_venta' => 200000, 'stock_actual' => 10, 'stock_minimo' => 4, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-005', 'nombre' => 'Nutrecan Croquetas Adulto 25kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 70000, 'precio_venta' => 95000, 'stock_actual' => 30, 'stock_minimo' => 10, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-006', 'nombre' => 'Chunky Pollo Adultos 18kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 85000, 'precio_venta' => 115000, 'stock_actual' => 25, 'stock_minimo' => 8, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-007', 'nombre' => 'Monello Perro Premium 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 90000, 'precio_venta' => 120000, 'stock_actual' => 12, 'stock_minimo' => 4, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-008', 'nombre' => 'Taste of the Wild Salmón 12.7kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 250000, 'precio_venta' => 320000, 'stock_actual' => 5, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-009', 'nombre' => 'Ringo Croquetas Adultos 30kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 80000, 'precio_venta' => 105000, 'stock_actual' => 40, 'stock_minimo' => 10, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-010', 'nombre' => 'Royal Canin Maxi Adulto 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 280000, 'precio_venta' => 350000, 'stock_actual' => 6, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'royal_canin.jpg'],
    ['codigo' => 'PUR-011', 'nombre' => 'Eukanuba Adulto Raza Mediana 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 210000, 'precio_venta' => 275000, 'stock_actual' => 10, 'stock_minimo' => 3, 'estado' => 1, 'imagen' => 'eukanuba.jpg'],
    ['codigo' => 'PUR-012', 'nombre' => 'Hill\'s Science Diet Adulto 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 320000, 'precio_venta' => 410000, 'stock_actual' => 4, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-013', 'nombre' => 'Orijen Original Perro 11.4kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 450000, 'precio_venta' => 560000, 'stock_actual' => 3, 'stock_minimo' => 1, 'estado' => 1, 'imagen' => 'orijen.jpg'],
    ['codigo' => 'PUR-014', 'nombre' => 'Acana Adult Dog 11.4kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 380000, 'precio_venta' => 480000, 'stock_actual' => 5, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-015', 'nombre' => 'Max Premium Especial Adultos 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 110000, 'precio_venta' => 145000, 'stock_actual' => 18, 'stock_minimo' => 5, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-016', 'nombre' => 'Dogourmet Carne Asada 15kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 65000, 'precio_venta' => 85000, 'stock_actual' => 30, 'stock_minimo' => 8, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-017', 'nombre' => 'Diamond Naturals Beef & Rice 18kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 230000, 'precio_venta' => 295000, 'stock_actual' => 7, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-018', 'nombre' => 'Italcol Campeón Adulto 30kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 75000, 'precio_venta' => 100000, 'stock_actual' => 35, 'stock_minimo' => 10, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-019', 'nombre' => 'Bravery Chicken Adult 12kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 260000, 'precio_venta' => 335000, 'stock_actual' => 6, 'stock_minimo' => 2, 'estado' => 1, 'imagen' => 'default.png'],
    ['codigo' => 'PUR-020', 'nombre' => 'Donkan Adultos Pollo y Carne 22kg', 'id_categoria' => $catId, 'unidad_medida' => 'Saco', 'precio_compra' => 60000, 'precio_venta' => 80000, 'stock_actual' => 45, 'stock_minimo' => 12, 'estado' => 1, 'imagen' => 'default.png']
];

DB::table('productos')->insertOrIgnore($productos);
echo "Productos insertados con exito.\n";
