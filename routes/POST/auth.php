<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../database/connection.php';

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Credenciales incompletas']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

global $conn;

$query = "SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciales incorrectas']);
    exit;
}

// Generar token simple (no persistente). Reemplazar por JWT en producción.
$token = bin2hex(random_bytes(16));

echo json_encode([
    'success' => true,
    'user' => [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email']
    ],
    'token' => $token
]);
