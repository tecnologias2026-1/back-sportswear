<?php

require_once __DIR__ . '/../database/conection.php';

// AGREGAR producto al carrito
function agregarCarritoModel($usuario_id, $producto_id, $cantidad) {
    global $conn;

    // 1. Obtener stock real del producto
    $sqlStock = "SELECT stock FROM productos WHERE id = ?";
    $stmtStock = $conn->prepare($sqlStock);
    $stmtStock->bind_param("i", $producto_id);
    $stmtStock->execute();
    $resStock = $stmtStock->get_result();
    $producto = $resStock->fetch_assoc();

    if (!$producto) {
        return ['error' => 'Producto no encontrado'];
    }

    $stockReal = $producto['stock'];

    // 2. Ver cuánto tiene ya en el carrito ese usuario
    $sqlCarrito = "SELECT cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?";
    $stmtCarrito = $conn->prepare($sqlCarrito);
    $stmtCarrito->bind_param("ii", $usuario_id, $producto_id);
    $stmtCarrito->execute();
    $resCarrito = $stmtCarrito->get_result();
    $itemCarrito = $resCarrito->fetch_assoc();

    $cantidadEnCarrito = $itemCarrito ? $itemCarrito['cantidad'] : 0;

    // 3. Verificar que no supere el stock disponible
    $stockDisponible = $stockReal - $cantidadEnCarrito;

    if ($cantidad > $stockDisponible) {
        return ['error' => "Solo hay $stockDisponible unidades disponibles"];
    }

    // 4. Agregar al carrito
    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    $stmt->execute();

    return ['success' => true];
}

// VER carrito de un usuario (con datos del producto)
function verCarritoModel($usuario_id) {
    global $conn;

    $sql = "SELECT c.id, c.cantidad, c.producto_id,
                   p.nombre, p.precio, p.imagen, p.stock,
                   (c.cantidad * p.precio) AS subtotal
            FROM carrito c
            JOIN productos p ON c.producto_id = p.id
            WHERE c.usuario_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}
// ACTUALIZAR cantidad de un item
function actualizarCantidadModel($id, $cantidad) {
    global $conn;

    $sql = "UPDATE carrito SET cantidad = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cantidad, $id);
    return $stmt->execute();
}

// ELIMINAR un producto del carrito
function eliminarItemCarritoModel($id) {
    global $conn;

    $sql = "DELETE FROM carrito WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// VACIAR todo el carrito de un usuario
function vaciarCarritoModel($usuario_id) {
    global $conn;

    $sql = "DELETE FROM carrito WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    return $stmt->execute();
}
function reducirStockModel($producto_id, $cantidad) {
    global $conn;
    $sql = "UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $producto_id, $cantidad);
    return $stmt->execute();
}
?>
