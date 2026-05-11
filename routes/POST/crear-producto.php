<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../database/connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['price'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos de producto incompletos']);
    exit;
}

$name = $data['name'];
$description = isset($data['description']) ? $data['description'] : null;
$price = $data['price'];
$stock = isset($data['stock']) ? (int)$data['stock'] : 0;

global $conn;

$query = "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssdi', $name, $description, $price, $stock);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear el producto']);
}
