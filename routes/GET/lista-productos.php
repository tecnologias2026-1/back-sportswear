<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../database/connection.php';

global $conn;

$query = "SELECT * FROM products";
$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener productos']);
    exit;
}

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
