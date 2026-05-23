<?php

header('Content-Type: application/json');

function envValue(array $names, $default)
{
    foreach ($names as $name) {
        $value = getenv($name);

        if ($value !== false && $value !== '') {
            return $value;
        }
    }

    return $default;
}

define('DB_HOST', envValue(['MYSQLHOST', 'DB_HOST'], 'localhost'));
define('DB_USER', envValue(['MYSQLUSER', 'DB_USER'], 'root'));
define('DB_PASSWORD', envValue(['MYSQLPASSWORD', 'DB_PASSWORD'], ''));
define('DB_NAME', envValue(['MYSQLDATABASE', 'DB_NAME'], 'sportswear'));
define('DB_PORT', (int) envValue(['MYSQLPORT', 'DB_PORT'], 3306));

$conn = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME,
    DB_PORT
);

if ($conn->connect_error) {
    http_response_code(500);

    die(json_encode([
        'success' => false,
        'message' => 'Error conectando a la base de datos',
        'error' => $conn->connect_error
    ]));
}

$conn->set_charset('utf8mb4');

?>