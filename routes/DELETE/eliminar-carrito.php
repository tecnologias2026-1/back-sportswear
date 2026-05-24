<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/carrito.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {

        $resultado = eliminarItemCarritoModel($data->id);

        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Producto eliminado del carrito"]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo eliminar"]);
        }

    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "id requerido"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>