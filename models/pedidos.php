<?php
require_once __DIR__ . '/../database/conection.php';

// CREAR pedido
function crearPedidoModel($usuario_id, $total) {
    global $conn;

    $sql = "INSERT INTO pedidos (usuario_id, fecha, total, estado)
            VALUES (?, NOW(), ?, 'pendiente')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $usuario_id, $total);
    return $stmt->execute();
}

// VER todos los pedidos (admin)
function verTodosPedidosModel() {
    global $conn;

    $sql = "SELECT p.*, u.nombre AS usuario_nombre
            FROM pedidos p
            JOIN usuario u ON p.usuario_id = u.id
            ORDER BY p.fecha DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

// VER pedidos de un usuario
function verPedidosUsuarioModel($usuario_id) {
    global $conn;

    $sql = "SELECT * FROM pedidos
            WHERE usuario_id = ?
            ORDER BY fecha DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

// CAMBIAR estado del pedido
function cambiarEstadoPedidoModel($id, $estado) {
    global $conn;

    $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $id);
    return $stmt->execute();
}

// CANCELAR pedido
function cancelarPedidoModel($id) {
    global $conn;

    $sql = "UPDATE pedidos SET estado = 'cancelado' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

?>