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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  echo json_encode(["mensaje" => "Método no permitido"]);
  exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
  $cat = getCategoriaByIdModel((int)$id);
  if ($cat) {
    echo json_encode($cat);
  } else {
    http_response_code(404);
    echo json_encode(["mensaje" => "Categoría no encontrada"]);
  }
  exit;
}

$items = getCategoriasModel();
echo json_encode($items);
