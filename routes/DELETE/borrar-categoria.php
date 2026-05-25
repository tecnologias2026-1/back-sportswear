<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

// Responder preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../../models/categorias.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido"]);
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    // accept body or query param
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id ?? null;
}

if (!$id) {
    http_response_code(400);
    echo json_encode(["mensaje" => "ID de categoría requerido"]);
    exit;
}

$ok = borrarCategoriaModel((int)$id);
if ($ok) {
    echo json_encode(["mensaje" => "Categoría borrada"]);
} else {
    http_response_code(503);
    echo json_encode(["mensaje" => "No se pudo borrar la categoría"]);
}
