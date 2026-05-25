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
function crearProductoModel($categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock = 0, $estado = 1) {
  global $conn;
  // Include stock and estado with defaults to avoid DB errors when columns are required
  $query = "INSERT INTO productos (categoria, nombre, descripcion, precio, imagen, marca, color, talla, stock, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssdssssii", $categoria, $nombre, $descripcion, $precio, $imagen, $marca, $color, $talla, $stock, $estado);
  if ($stmt->execute()) {
    return ["success" => true, "id" => $conn->insert_id];
  }
  return false;
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

