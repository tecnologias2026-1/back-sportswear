<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../models/productos.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $productos = getProductosModel();
    echo json_encode($productos);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
