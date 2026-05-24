<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

    if ($usuario_id > 0) {
        $carrito = verCarritoModel($usuario_id);
        http_response_code(200);
        echo json_encode($carrito);
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "usuario_id requerido"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>