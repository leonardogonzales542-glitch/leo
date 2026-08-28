<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/producto.php';

// Si no se ha configurado la API Key, deberás colocarla aquí o en database.php
$api_key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : 'AQUI_TU_API_KEY';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$user_message = $_POST['message'] ?? '';
$history_json = $_POST['history'] ?? '[]';
$history = json_decode($history_json, true) ?: [];

// Validar que el mensaje no esté vacío
if (empty($user_message)) {
    echo json_encode(['status' => 'error', 'message' => 'Mensaje vacío.']);
    exit;
}

// Validar que exista la API Key de Gemini
if ($api_key === 'AQUI_TU_API_KEY' || empty($api_key)) {
    echo json_encode(['status' => 'error', 'message' => 'La API Key de Gemini no está configurada. Por favor, avisa al administrador.']);
    exit;
}

// 1. Obtener productos activos para dar contexto a la IA
// Consultamos a la base de datos para recuperar todos los productos y construir
// un texto de contexto que será inyectado en el prompt de la IA.
$productoModel = new Producto($conn);
$productosResult = $productoModel->getAll();

$productosContext = "Catálogo de Productos Disponibles en PetInsumos:\n";
while ($row = $productosResult->fetch_assoc()) {
    if ($row['estado'] == 1) { // Solo activos
        $productosContext .= "- Nombre: {$row['nombre']} | Categoría: {$row['categoria_nombre']} | Precio: $" . number_format($row['precio_venta'], 0, ',', '.') . " | Stock: {$row['stock_actual']} {$row['unidad_medida']} | Descripción: {$row['descripcion']}\n";
    }
}

// 2. Construir el Prompt del Sistema
$system_instruction = "Eres el asistente virtual amable y experto de 'PetInsumos', una tienda de purinas y productos para mascotas.
Tu objetivo es ayudar a los clientes respondiendo preguntas sobre productos, recomendando opciones según el tipo de mascota, informando precios y stock.
Usa la siguiente información del catálogo para tus respuestas. Si un producto no está en la lista, indica educadamente que no lo tenemos por el momento.
NO inventes precios ni productos que no estén en el catálogo. Sé conciso y amigable.

" . $productosContext;

// 3. Formatear historial para la API de Gemini
// Gemini requiere un formato estricto de array para el historial conversacional.
$contents = [];

// Convertir historial previo recibido por POST al formato {"role": ..., "parts": ...}
foreach ($history as $msg) {
    if (isset($msg['role']) && isset($msg['content'])) {
        $contents[] = [
            "role" => $msg['role'] === 'user' ? 'user' : 'model',
            "parts" => [["text" => $msg['content']]]
        ];
    }
}

// Añadir el mensaje actual enviado por el usuario al final del historial
$contents[] = [
    "role" => "user",
    "parts" => [["text" => $user_message]]
];

$payload = [
    "system_instruction" => [
        "parts" => [
            "text" => $system_instruction
        ]
    ],
    "contents" => $contents,
    "generationConfig" => [
        "temperature" => 0.4, // Un poco creativo pero apegado a los datos
        "maxOutputTokens" => 500,
    ]
];

// 4. Llamar a la API de Google Gemini mediante cURL
// Enviamos la petición POST al endpoint de Gemini con la configuración establecida.
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=" . trim($api_key);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retornar respuesta como string en lugar de imprimirla
curl_setopt($ch, CURLOPT_POST, true);           // Método POST
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para desarrollo local en Windows/XAMPP/Laragon

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $error]);
    exit;
}

if ($http_code !== 200) {
    // Para ver qué error devolvió Gemini
    $errData = json_decode($response, true);
    $errMsg = $errData['error']['message'] ?? 'Error en la API de IA';
    echo json_encode(['status' => 'error', 'message' => 'Error de API (' . $http_code . '): ' . $errMsg]);
    exit;
}

$responseData = json_decode($response, true);

if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $responseData['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode(['status' => 'success', 'reply' => trim($reply)]);
} else {

    echo json_encode(['status' => 'error', 'message' => 'Respuesta inesperada de la IA.']);
}
