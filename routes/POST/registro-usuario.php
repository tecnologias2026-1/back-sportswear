<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../models/user.model.php';


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['email'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];
$role = isset($data['role']) ? $data['role'] : 'user';

$res = createUserModel($name, $email, $password, $role);

if ($res && isset($res['success']) && $res['success']) {
    echo json_encode(['success' => true, 'id' => $res['id']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo crear el usuario']);
}
