<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=bdtienda', 'root', '');
    echo "Conectado PDO 3306\n";
} catch(Exception $e) {
    echo "Error PDO 3306: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=bdtienda', 'root', '');
    echo "Conectado PDO 3307\n";
} catch(Exception $e) {
    echo "Error PDO 3307: " . $e->getMessage() . "\n";
}
