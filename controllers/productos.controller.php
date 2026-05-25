<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

// Responder preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../models/productos.php';

echo getProductosModel();
function getAllProductos() {
    $productos = getProductosModel();
    if ($productos === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener los productos']);
        return;
    }
    echo json_encode($productos);
}
