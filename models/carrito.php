<?php

require_once __DIR__ . '/../database/conexion.php';

function agregarCarritoModel($usuario_id, $producto_id, $cantidad) {

    global $conn;

    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iii",
        $usuario_id,
        $producto_id,
        $cantidad
    );

    return $stmt->execute();
}

?>