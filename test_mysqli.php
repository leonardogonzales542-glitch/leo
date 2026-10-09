<?php
$conn = new mysqli('localhost', 'root', '', 'bdtienda', 3307);
if ($conn->connect_error) {
    echo "Error mysqli localhost 3307: " . $conn->connect_error . "\n";
} else {
    echo "Conectado mysqli localhost 3307\n";
}
$conn2 = new mysqli('localhost', 'root', '', 'bdtienda', 3306);
if ($conn2->connect_error) {
    echo "Error mysqli localhost 3306: " . $conn2->connect_error . "\n";
} else {
    echo "Conectado mysqli localhost 3306\n";
}
