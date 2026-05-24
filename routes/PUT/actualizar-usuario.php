<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../models/user.model.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $raw = file_get_contents("php://input");

    // Try JSON first
    $data = json_decode($raw);

    // If not JSON, try parsing as form-encoded
    if ($data === null) {
        parse_str($raw, $parsed);
        $data = new stdClass();
        foreach ($parsed as $k => $v) {
            $data->{$k} = $v;
        }
    }

    // Obtener ID de la URL o del JSON/form
    $id = isset($_GET['id']) ? $_GET['id'] : (isset($data->id) ? $data->id : null);

    $nombre = isset($data->nombre) ? trim($data->nombre) : '';
    $correo = isset($data->correo) ? trim($data->correo) : '';
    $rol = isset($data->rol) && $data->rol !== '' ? $data->rol : 'Usuario';

    $missing = [];
    if (empty($id)) $missing[] = 'id';
    if ($nombre === '') $missing[] = 'nombre';
    if ($correo === '') $missing[] = 'correo';

    if (!empty($missing)) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. Faltan: " . implode(', ', $missing), "received_raw" => $raw]);
        exit;
    }

    $resultado = updateUserModel((int)$id, $nombre, $correo, $rol);

    if ($resultado) {
        http_response_code(200);
        echo json_encode(["mensaje" => "Usuario actualizado exitosamente."]);
    } else {
        http_response_code(503);
        echo json_encode(["mensaje" => "No se pudo actualizar el usuario."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}