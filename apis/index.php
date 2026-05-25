<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

// Responder preflight OPTIONS
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

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api', '', $path);
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// ── USUARIOS ─────────────────────────────────────────────────
if ($method === 'GET' && ($path === '/users' || $path === '/usuarios')) {
    require_once __DIR__ . '/../routes/GET/lista-usuarios.php';
    exit;
}

if ($method === 'GET' && preg_match('#^/(users|usuarios)/(\d+)$#', $path, $m)) {
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

// ── PRODUCTOS ─────────────────────────────────────────────────
if ($method === 'GET' && ($path === '/products' || $path === '/productos')) {
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
// Categories endpoints
if ($method === 'GET' && ($path === '/categorias' || $path === '/categories')) {
    require_once __DIR__ . '/../routes/GET/lista-categorias.php';
    exit;
}

if ($method === 'GET' && preg_match('#^/(categorias|categories)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/GET/lista-categorias.php';
    exit;
}

if ($method === 'POST' && ($path === '/categorias' || $path === '/categories' || $path === '/crear-categoria')) {
    require_once __DIR__ . '/../routes/POST/crear-categoria.php';
    exit;
}

if ($method === 'PUT' && preg_match('#^/(categorias|categories)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/PUT/actualizar-categoria.php';
    exit;
}

if ($method === 'DELETE' && preg_match('#^/(categorias|categories)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/DELETE/borrar-categoria.php';
    exit;
}
elseif ($path === '' || $path === '/')

// ── CATEGORIAS ────────────────────────────────────────────────
if ($method === 'GET' && ($path === '/categories' || $path === '/categorias')) {
    require_once __DIR__ . '/../routes/GET/lista-categorias.php';
    exit;
}

if ($method === 'GET' && preg_match('#^/(categories|categorias)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/GET/lista-categorias.php';
    exit;
}

if ($method === 'POST' && ($path === '/categories' || $path === '/categorias' || $path === '/crear-categoria')) {
    require_once __DIR__ . '/../routes/POST/crear-categoria.php';
    exit;
}

if ($method === 'PUT' && preg_match('#^/(categories|categorias)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/PUT/actualizar-categoria.php';
    exit;
}

if ($method === 'DELETE' && preg_match('#^/(categories|categorias)/(\d+)$#', $path, $m)) {
    $_GET['id'] = $m[2];
    require_once __DIR__ . '/../routes/DELETE/borrar-categoria.php';
    exit;
}

// ── CARRITO ───────────────────────────────────────────────────
if ($method === 'GET' && $path === '/carrito') {
    require_once __DIR__ . '/../routes/GET/ver-carrito.php';
    exit;
}

if ($method === 'POST' && $path === '/carrito') {
    require_once __DIR__ . '/../routes/POST/agregar-carrito.php';
    exit;
}

if ($method === 'PUT' && $path === '/carrito') {
    require_once __DIR__ . '/../routes/PUT/actualizar-carrito.php';
    exit;
}

if ($method === 'DELETE' && $path === '/carrito' && isset($_GET['vaciar'])) {
    require_once __DIR__ . '/../routes/DELETE/vaciar-carrito.php';
    exit;
}

if ($method === 'DELETE' && $path === '/carrito') {
    require_once __DIR__ . '/../routes/DELETE/eliminar-carrito.php';
    exit;
}

// ── PEDIDOS ───────────────────────────────────────────────────
if ($method === 'GET' && $path === '/pedidos') {
    require_once __DIR__ . '/../routes/GET/ver-pedidos.php';
    exit;
}

if ($method === 'POST' && $path === '/pedidos') {
    require_once __DIR__ . '/../routes/POST/crear-pedido.php';
    exit;
}

if ($method === 'PUT' && $path === '/pedidos') {
    require_once __DIR__ . '/../routes/PUT/cambiar-estado-pedido.php';
    exit;
}

if ($method === 'DELETE' && $path === '/pedidos') {
    require_once __DIR__ . '/../routes/DELETE/cancelar-pedido.php';
    exit;
}

// ── RAÍZ ──────────────────────────────────────────────────────
if ($path === '/') {
    http_response_code(200);
    echo json_encode(['message' => 'Servidor backend activo']);
}
?>