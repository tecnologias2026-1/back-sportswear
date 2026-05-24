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
    $raw = file_get_contents("php://input");

    // Try JSON body
    $data = json_decode($raw);
    if ($data === null) {
        parse_str($raw, $parsed);
        $data = new stdClass();
        foreach ($parsed as $k => $v) {
            $data->{$k} = $v;
        }
    }

    // Obtener ID de la URL (set por front controller), querystring o body
    $id = isset($_GET['id']) ? $_GET['id'] : (isset($data->id) ? $data->id : null);

    if (!empty($id)) {
        $resultado = deleteUserModel((int)$id);

        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Usuario borrado exitosamente.", "id" => (int)$id]);
        } else {
            http_response_code(503);
            echo json_encode(["mensaje" => "No se pudo borrar el usuario.", "id" => (int)$id]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos. ID de usuario requerido.", "received_raw" => $raw]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}