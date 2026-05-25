<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../database/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->cantidad)) {
        $sql = "UPDATE pedido_items SET cantidad = ?, subtotal = precio * ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $data->cantidad, $data->cantidad, $data->id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Detalle actualizado"]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo actualizar"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "id y cantidad requeridos"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>