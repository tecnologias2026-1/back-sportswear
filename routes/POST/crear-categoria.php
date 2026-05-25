<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/categorias.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (empty($data->nombre)) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Nombre de categoría requerido"]);
        exit;
    }
    $nombre = $data->nombre;
    $descripcion = $data->descripcion ?? '';

    $res = crearCategoriaModel($nombre, $descripcion);
    if ($res && is_array($res) && isset($res['id'])) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Categoría creada", "id" => $res['id']]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo crear la categoría"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido"]);
}
