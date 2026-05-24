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
    // If an ID is provided (via front controller or query param), return single user
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if (!empty($id)) {
        $usuario = getUserByIdModel($id);
        if ($usuario === null) {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
        } else {
            echo json_encode($usuario);
        }
        
    } else {
        $usuarios = getUsersModel();
        echo json_encode($usuarios);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
