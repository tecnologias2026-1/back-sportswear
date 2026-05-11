<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../models/user.model.php';

$users = getUsersModel();

if ($users === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener usuarios']);
    exit;
}

echo json_encode($users);
