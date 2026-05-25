<?php

require_once __DIR__ . '/../database/conection.php';

// Listar todos los productos
function getProductosModel() {
  global $conn;
  $query = "SELECT * FROM productos";
  $result = $conn->query($query);
  $productos = [];
  while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
  return $productos;
}

// Obtener un producto por id
function getProductoByIdModel($id) {
  global $conn;
  $query = "SELECT * FROM productos WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $resultado = $stmt->get_result();
  return $resultado->fetch_assoc();
}

// Crear un producto
function crearProductoModel($categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock = 0, $estado = 1) {
  global $conn;
  $query = "INSERT INTO productos (categoria, nombre, descripcion, precio, imagen, marca, color, talla, stock, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssdssssii", $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock, $estado);
  if ($stmt->execute()) {
    return ["success" => true, "id" => $conn->insert_id];
  }
  return false;
}

// Actualizar un producto
function actualizarProductoModel($id, $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock = 0) {
  global $conn;
  $query = "UPDATE productos SET categoria=?, nombre=?, descripcion=?, precio=?, imagen=?, marca=?, color=?, talla=?, stock=? WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssdssssii", $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock, $id);
  return $stmt->execute();
}

// Borrar un producto
function borrarProductoModel($id) {
  global $conn;
  $query = "DELETE FROM productos WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  return $stmt->execute();
}