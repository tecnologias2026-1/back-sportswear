<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../database/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

    if ($pedido_id > 0) {
        $sql = "SELECT * FROM pedido_items WHERE pedido_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $items = $resultado->fetch_all(MYSQLI_ASSOC);
        echo json_encode($items);
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "pedido_id requerido"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>