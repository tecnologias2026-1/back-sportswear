<?php
require_once __DIR__ . '/../database/conection.php';

// AGREGAR producto al carrito
function agregarCarritoModel($usuario_id, $producto_id, $cantidad) {
    global $conn;

    // Si ya existe ese producto en el carrito del usuario, suma la cantidad
    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    return $stmt->execute();
}

// VER carrito de un usuario (con datos del producto)
function verCarritoModel($usuario_id) {
    global $conn;

    $sql = "SELECT c.id, c.cantidad, c.producto_id,
                   p.nombre, p.precio, p.imagen,
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

?>