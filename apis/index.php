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

// Allow both /api prefixed and root paths
$path = str_replace('/api', '', $path);
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// Route requests
// Normalize paths and map to existing route scripts. Support English/Spanish synonyms.
// Users endpoints
if ($method === 'GET' && ($path === '/users' || $path === '/usuarios')) {
    require_once __DIR__ . '/../routes/GET/lista-usuarios.php';
    exit;
}

if ($method === 'GET' && preg_match('#^/(users|usuarios)/(\d+)$#', $path, $m)) {
    // If needed, set GET id param and include handler that reads it from $_GET or body
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/GET/lista-usuarios.php';
    exit;
}

if ($method === 'POST' && ($path === '/users' || $path === '/registro-usuario' || $path === '/register')) {
    require_once __DIR__ . '/../routes/POST/registro-usuario.php';
    exit;
}

if ($method === 'POST' && ($path === '/auth' || $path === '/login')) {
    require_once __DIR__ . '/../routes/POST/auth.php';
    exit;
}

if ($method === 'PUT' && preg_match('#^/(users|usuarios)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/PUT/actualizar-usuario.php';
    exit;
}

if ($method === 'DELETE' && preg_match('#^/(users|usuarios)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/DELETE/borrar-usuario.php';
    exit;
}

// Products endpoints
if ($method === 'GET' && ($path === '/products' || $path === '/productos' || $path === '/productos')) {
    require_once __DIR__ . '/../routes/GET/lista-productos.php';
    exit;
}

if ($method === 'GET' && preg_match('#^/(products|productos)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/GET/lista-productos.php';
    exit;
}

if ($method === 'POST' && ($path === '/products' || $path === '/productos' || $path === '/crear-producto')) {
    require_once __DIR__ . '/../routes/POST/crear-producto.php';
    exit;
}

if ($method === 'PUT' && preg_match('#^/(products|productos)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/PUT/actualizar-producto.php';
    exit;
}

if ($method === 'DELETE' && preg_match('#^/(products|productos)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/DELETE/borrar-producto.php';
    exit;
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