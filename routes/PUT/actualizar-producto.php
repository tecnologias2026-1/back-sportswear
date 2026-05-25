<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/productos.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    
    $id = $_GET['id'] ?? ($data->id ?? null);

    if(!empty($id) && !empty($data->nombre) && !empty($data->precio)) {
        $categoria   = $data->categoria ?? '';
        $descripcion = $data->descripcion ?? '';
        $imagen      = $data->imagen ?? '';
        $marca       = $data->marca ?? '';
        $color       = $data->color ?? '';
        $talla       = $data->talla ?? '';
        $stock       = $data->stock ?? 0;

        $resultado = actualizarProductoModel($id, $categoria, $data->nombre, $descripcion, $data->precio, $imagen, $marca, $color, $talla, $stock);
        
        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Producto actualizado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo actualizar el producto."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. ID, nombre y precio requeridos."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}