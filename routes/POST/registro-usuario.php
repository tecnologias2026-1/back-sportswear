<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->nombre) && !empty($data->correo) && !empty($data->contraseña)) {
        $rol = !empty($data->rol) ? $data->rol : 'Usuario'; // Rol por defecto

        $resultado = createUserModel($data->nombre, $data->correo, $data->contraseña, $rol);
        
        if ($resultado) {
            http_response_code(201);
            echo json_encode(["mensaje" => "Usuario registrado exitosamente.", "id" => $resultado['id']]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo registrar el usuario."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. Nombre, correo y contraseña son requeridos."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
