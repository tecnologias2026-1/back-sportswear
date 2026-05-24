<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/pedidos.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    $estadosValidos = ['pendiente', 'en proceso', 'enviado', 'entregado', 'cancelado'];

    if (!empty($data->id) && !empty($data->estado)) {

        if (!in_array($data->estado, $estadosValidos)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Estado inválido. Opciones: " . implode(', ', $estadosValidos)]);
            exit();
        }

        $resultado = cambiarEstadoPedidoModel($data->id, $data->estado);

        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Estado actualizado a '{$data->estado}'"]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo actualizar el estado"]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. Se requiere id y estado"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>