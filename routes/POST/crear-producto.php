<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/productos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->nombre) && !empty($data->precio)) {
        $categoria = $data->categoria ?? '';
        $descripcion = $data->descripcion ?? '';
        $imagen = $data->imagen ?? '';
        $marca = $data->marca ?? '';
        $color = $data->color ?? '';
        $talla = $data->talla ?? '';

        $resultado = crearProductoModel($categoria, $data->nombre, $descripcion, $data->precio, $imagen, $marca, $color, $talla);

        if ($resultado && is_array($resultado) && isset($resultado['id'])) {
            http_response_code(201);
            echo json_encode(["mensaje" => "Producto creado exitosamente.", "id" => $resultado['id']]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo crear el producto."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. Nombre y precio requeridos."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
