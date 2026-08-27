<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/producto.php';
require_once __DIR__ . '/../models/pedido.php';

$api_key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$user_message = $_POST['message'] ?? '';
$history_json = $_POST['history'] ?? '[]';
$history = json_decode($history_json, true) ?: [];

if (empty($user_message)) {
    echo json_encode(['status' => 'error', 'message' => 'Mensaje vacío.']);
    exit;
}

if (empty($api_key) || $api_key === 'AQUI_TU_API_KEY') {
    echo json_encode(['status' => 'success', 'reply' => '¡Hola! Actualmente estoy en **Modo Demostración** porque la API Key no ha sido configurada.']);
    exit;
}

// 1. Obtener productos activos
$productoModel = new Producto($conn);
$productosResult = $productoModel->getAll();

$productosContext = "Catálogo de Productos Disponibles en PetInsumos:\n";
while ($row = $productosResult->fetch_assoc()) {
    if ($row['estado'] == 1) { 
        $productosContext .= "- ID: {$row['id_producto']} | Nombre: {$row['nombre']} | Categoría: {$row['categoria_nombre']} | Precio: $" . number_format($row['precio_venta'], 0, ',', '.') . " | Stock: {$row['stock_actual']} {$row['unidad_medida']} | Descripción: {$row['descripcion']}\n";
    }
}

// 2. Prompt del Sistema
$system_instruction = "Eres el asistente virtual amable y experto de 'PetInsumos', una tienda de purinas y productos para mascotas.
Tu objetivo es ayudar a los clientes respondiendo preguntas sobre productos, recomendando opciones según el tipo de mascota y su raza, informando precios y stock.

Instrucciones especiales para recomendaciones:
- Cuando un cliente pida una recomendación de alimento o producto, pregúntale por la raza y edad de su mascota si no la ha proporcionado.
- Basa tus recomendaciones en las necesidades nutricionales típicas de cada raza (ej. tamaño de croqueta para razas pequeñas/grandes, cuidado articular, pelaje, posibles alergias o estómagos sensibles de ciertas razas, etc.).
- Relaciona esas necesidades específicas de la raza con las descripciones de los productos en nuestro catálogo para sugerir la mejor opción disponible.

IMPORTANTE SOBRE PEDIDOS:
Cuando el cliente indique claramente que desea comprar o confirme su compra de uno o más productos sugeridos, DEBES incluir al final de tu respuesta, y SOLO al final, un bloque de código JSON con este formato exacto:
```json
{
  \"action\": \"create_order\",
  \"productos\": [
    {\"id_producto\": ID, \"cantidad\": CANTIDAD, \"precio\": PRECIO, \"subtotal\": SUBTOTAL}
  ],
  \"total\": TOTAL
}
```
Asegúrate de que el bloque de código comience con ```json y termine con ```. 
Usa los `id_producto` y precios reales del catálogo. Antes del bloque JSON puedes despedirte o confirmar verbalmente la compra.

Usa la siguiente información del catálogo para tus respuestas. Si un producto ideal no está en la lista, indica educadamente qué tipo de producto sería bueno y ofrécele la alternativa más cercana que tengamos.
NO inventes precios ni productos que no estén en el catálogo. Sé conciso y amigable.

" . $productosContext;

// 3. Formatear historial
$contents = [];
foreach ($history as $msg) {
    if (isset($msg['role']) && isset($msg['content'])) {
        $contents[] = [
            "role" => $msg['role'] === 'user' ? 'user' : 'model',
            "parts" => [["text" => $msg['content']]]
        ];
    }
}
$contents[] = [
    "role" => "user",
    "parts" => [["text" => $user_message]]
];

$payload = [
    "system_instruction" => [
        "parts" => ["text" => $system_instruction]
    ],
    "contents" => $contents,
    "generationConfig" => [
        "temperature" => 0.4,
        "maxOutputTokens" => 2000,
    ]
];

// 4. API de Google Gemini
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . trim($api_key);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Evitar error HTTP/2 stream en local

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión cURL: ' . $error]);
    exit;
}

if ($http_code !== 200) {
    $errData = json_decode($response, true);
    $errMsg = $errData['error']['message'] ?? 'Error desconocido';
    
    if ($http_code === 503) {
        echo json_encode(['status' => 'success', 'reply' => '¡Conexión exitosa! Sin embargo, los servidores de Google (modelo gemini-flash-latest) están saturados ahora mismo (Error 503). Por favor, intenta enviar tu mensaje de nuevo en unos momentos.']);
        exit;
    }
    
    if ($http_code === 429) {
        echo json_encode(['status' => 'success', 'reply' => '¡Vaya! He alcanzado mi límite de consultas por minuto. Por favor, espera alrededor de 20-30 segundos e inténtalo de nuevo. 🐾']);
        exit;
    }
    
    // Si es 404, intentar listar los modelos disponibles para ver a qué tiene acceso la llave
    if ($http_code === 404) {
        $diagUrl = "https://generativelanguage.googleapis.com/v1beta/models?key=" . trim($api_key);
        $diagCh = curl_init($diagUrl);
        curl_setopt($diagCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($diagCh, CURLOPT_SSL_VERIFYPEER, false);
        $diagResp = curl_exec($diagCh);
        curl_close($diagCh);
        
        $diagData = json_decode($diagResp, true);
        $modelNames = [];
        if (isset($diagData['models'])) {
            foreach ($diagData['models'] as $m) {
                if (strpos($m['name'], 'gemini') !== false) {
                    $modelNames[] = str_replace('models/', '', $m['name']);
                }
            }
        }
        
        if (!empty($modelNames)) {
            $modelList = implode(", ", $modelNames);
            echo json_encode(['status' => 'success', 'reply' => 'La conexión funciona, pero el modelo que intentamos usar no fue encontrado o no soporta generateContent (404). Modelos habilitados para tu llave: **' . $modelList . '**.']);
            exit;
        } else {
            echo json_encode(['status' => 'success', 'reply' => 'Tu llave es detectada, pero parece que no tiene NINGÚN modelo de IA habilitado. Revisa tu cuenta en Google Cloud o AI Studio. Detalle: ' . $errMsg]);
            exit;
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Error API Gemini (' . $http_code . '): ' . $errMsg]);
    exit;
}

$responseData = json_decode($response, true);

if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $responseData['candidates'][0]['content']['parts'][0]['text'];
    
    // Buscar si hay una orden en el bloque JSON
    if (preg_match('/```json\s*(\{.*"action":\s*"create_order".*\})\s*```/is', $reply, $matches)) {
        $json_str = $matches[1];
        $order_data = json_decode($json_str, true);
        
        // Quitar el bloque JSON de la respuesta que verá el usuario
        $reply = preg_replace('/```json\s*(\{.*"action":\s*"create_order".*\})\s*```/is', '', $reply);
        
        if ($order_data && isset($_SESSION['usuario'])) {
            // Lógica para crear el pedido
            $nombre_usuario = $_SESSION['usuario']['usuario'];
            $email_usuario = $_SESSION['usuario']['email'];
            
            // 1. Buscar o crear cliente
            $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE email = ? OR nombre = ? LIMIT 1");
            $stmt->bind_param("ss", $email_usuario, $nombre_usuario);
            $stmt->execute();
            $resCliente = $stmt->get_result();
            
            if ($resCliente->num_rows > 0) {
                $row = $resCliente->fetch_assoc();
                $id_cliente = $row['id_cliente'];
            } else {
                // Crear cliente
                $stmtInsert = $conn->prepare("INSERT INTO clientes (numero_documento, nombre, email, estado) VALUES (?, ?, ?, 1)");
                $doc_temporal = 'C-' . time();
                $stmtInsert->bind_param("sss", $doc_temporal, $nombre_usuario, $email_usuario);
                $stmtInsert->execute();
                $id_cliente = $conn->insert_id;
            }
            
            // 2. Determinar vendedor (buscar un admin válido)
            $stmtVendedor = $conn->query("SELECT id_usuario FROM usuarios WHERE id_rol = 1 LIMIT 1");
            if ($stmtVendedor && $stmtVendedor->num_rows > 0) {
                $id_vendedor = $stmtVendedor->fetch_assoc()['id_usuario'];
            } else {
                // Si no hay admin, buscar cualquier usuario
                $stmtCualquiera = $conn->query("SELECT id_usuario FROM usuarios LIMIT 1");
                $id_vendedor = ($stmtCualquiera && $stmtCualquiera->num_rows > 0) ? $stmtCualquiera->fetch_assoc()['id_usuario'] : NULL;
            }
            
            $subtotal = $order_data['total'];
            $iva = 0;
            $total = $order_data['total'];
            $metodo_pago = 'Pendiente';
            $entrega = 'Local';
            $detalles = $order_data['productos'];
            
            // Auto-reparar la tabla pedidos si le faltan las columnas nuevas
            $res = $conn->query("SHOW COLUMNS FROM pedidos LIKE 'metodo_pago'");
            if ($res->num_rows == 0) {
                $conn->query("ALTER TABLE pedidos ADD COLUMN metodo_pago VARCHAR(50) DEFAULT 'Pendiente'");
            }
            $res = $conn->query("SHOW COLUMNS FROM pedidos LIKE 'entrega'");
            if ($res->num_rows == 0) {
                $conn->query("ALTER TABLE pedidos ADD COLUMN entrega VARCHAR(150) DEFAULT 'Local'");
            }
            
            $pedidoModel = new Pedido($conn);
            $id_pedido = $pedidoModel->createPedido($id_cliente, $id_vendedor, $subtotal, $iva, $total, $metodo_pago, $entrega, $detalles);
            
            if (is_numeric($id_pedido)) {
                // Modificar el mensaje final
                $reply .= "\n\n¡Excelente! Tu pedido #" . $id_pedido . " ha sido creado exitosamente en nuestro sistema y está en estado Pendiente. ¡Gracias por tu compra!";
            } else {
                $errorMsg = isset($id_pedido['error']) ? $id_pedido['error'] : json_encode($id_pedido);
                $reply .= "\n\nHubo un pequeño problema al procesar tu pedido de forma automática. Por favor contacta a soporte. Detalle técnico: " . $errorMsg;
            }
        } else if (!isset($_SESSION['usuario'])) {
             $reply .= "\n\n*Nota: Para procesar tu pedido automáticamente debes iniciar sesión.*";
        }
    }

    echo json_encode(['status' => 'success', 'reply' => trim($reply)]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Respuesta inesperada de la IA.']);
}
