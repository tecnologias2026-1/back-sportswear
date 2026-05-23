<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = getUsersModel();
    echo json_encode($usuarios);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
