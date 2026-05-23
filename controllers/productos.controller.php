<?php
require_once __DIR__ . '/../models/productos.php';

echo getProductosModel();
function getAllProductos() {
    $productos = getProductosModel();
    if ($productos === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener los productos']);
        return;
    }
    echo json_encode($productos);
}
