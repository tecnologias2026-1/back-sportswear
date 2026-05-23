<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    
    // Obtener ID de la URL o del JSON
    $id = $_GET['id'] ?? ($data->id ?? null);

    if(!empty($id)) {
        $resultado = deleteUserModel($id);
        
        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Usuario borrado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo borrar el usuario."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. ID de usuario requerido."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}