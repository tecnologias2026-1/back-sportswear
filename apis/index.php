<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
header("Content-Type: application/json");
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
if (strpos($path, '/usuario') === 0) {

    require_once 'routes/user.routes.php';

    handleUserRoutes($method, $path);

}
elseif (strpos($path, '/productos') === 0) {

    // Map GET /api/productos to the existing route script
    if ($method === 'GET') {
       require_once __DIR__ . '/routes/GET/lista-productos.php';
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
    
    

}
elseif ($path === '' || $path === '/') {

    http_response_code(200);

    echo json_encode([
        'message' => 'Servidor backend activo'
    ]);

}
elseif (strpos($path, '/carrito') === 0) {
    if ($method === 'GET') {
        require_once __DIR__ . '/../routes/GET/ver-carrito.php';
    } elseif ($method === 'POST') {
        require_once __DIR__ . '/../routes/POST/agregar-carrito.php';
    } elseif ($method === 'PUT') {
        require_once __DIR__ . '/../routes/PUT/actualizar-carrito.php';
    } elseif ($method === 'DELETE') {
        $usuario_id = $_GET['vaciar'] ?? null;
        if ($usuario_id) {
            require_once __DIR__ . '/../routes/DELETE/vaciar-carrito.php';
        } else {
            require_once __DIR__ . '/../routes/DELETE/eliminar-carrito.php';
        }
    }
}
elseif (strpos($path, '/pedidos') === 0) {
    if ($method === 'GET') {
        require_once __DIR__ . '/../routes/GET/ver-pedidos.php';
    } elseif ($method === 'POST') {
        require_once __DIR__ . '/../routes/POST/crear-pedido.php';
    } elseif ($method === 'PUT') {
        require_once __DIR__ . '/../routes/PUT/cambiar-estado-pedido.php';
    } elseif ($method === 'DELETE') {
        require_once __DIR__ . '/../routes/DELETE/cancelar-pedido.php';
    }
}
?>