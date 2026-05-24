<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id) && !empty($data->cantidad)) {

        $resultado = actualizarCantidadModel($data->id, $data->cantidad);

        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Cantidad actualizada"]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo actualizar"]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>