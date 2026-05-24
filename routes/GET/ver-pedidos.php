<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/pedidos.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

    if ($usuario_id > 0) {
        // Ver pedidos de un usuario específico
        $pedidos = verPedidosUsuarioModel($usuario_id);
    } else {
        // Sin usuario_id devuelve todos (para admin)
        $pedidos = verTodosPedidosModel();
    }

    http_response_code(200);
    echo json_encode($pedidos);

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>  