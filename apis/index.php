<?php

spl_autoload_register(function ($class) {

    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }

});

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = str_replace('/api', '', $path);

// Route requests
if (strpos($path, '/usuarios') === 0) {

    require_once 'routes/user.routes.php';

    handleUserRoutes($method, $path);

}
elseif ($path === '/api') {

    http_response_code(200);

    echo json_encode([
        'message' => 'Servidor backend activo'
    ]);

}
else {

    http_response_code(404);

    echo json_encode([
        'error' => 'Ruta no encontrada'
    ]);

}
?>