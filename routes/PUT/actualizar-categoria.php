<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/categorias.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(["mensaje" => "ID de categoría requerido en la URL"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre ?? null;
    $descripcion = $data->descripcion ?? '';
    if (!$nombre) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Nombre requerido"]);
        exit;
    }
    $ok = actualizarCategoriaModel((int)$id, $nombre, $descripcion);
    if ($ok) {
        echo json_encode(["mensaje" => "Categoría actualizada"]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo actualizar"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido"]);
}
