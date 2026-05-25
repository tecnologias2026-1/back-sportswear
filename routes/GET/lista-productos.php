<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/productos.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $producto = getProductoByIdModel($id);
        if ($producto) {
            echo json_encode($producto);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado"]);
        }
    } else {
        $productos = getProductosModel();
        echo json_encode($productos);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}