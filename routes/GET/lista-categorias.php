<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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
