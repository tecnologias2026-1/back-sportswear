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

// Crear un producto
function crearProductoModel($categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla) {
  global $conn;
  $query = "INSERT INTO productos (categoria, nombre, descripcion, precio, imagen, marca, color, talla) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssdssss", $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla);
  return $stmt->execute();
}

// Actualizar un producto
function actualizarProductoModel($id, $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla) {
  global $conn;
  $query = "UPDATE productos SET categoria=?, nombre=?, descripcion=?, precio=?, imagen=?, marca=?, color=?, talla=? WHERE id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssdssssi", $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $id);
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

