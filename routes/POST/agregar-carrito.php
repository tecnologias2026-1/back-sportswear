<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
     http_response_code(200);
        exit();
}

require_once __DIR__ . '/../../models/carrito.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->usuario_id) &&
            !empty($data->producto_id) &&
            !empty($data->cantidad)
        ) {

            $resultado = agregarCarritoModel(
                $data->usuario_id,
                $data->producto_id,
                $data->cantidad
            );

            if ($resultado) {
                http_response_code(201);
                echo json_encode(["mensaje" => "Producto agregado al carrito"]);
            } else {
                http_response_code(503);
                echo json_encode(["mensaje" => "No se pudo agregar"]);
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