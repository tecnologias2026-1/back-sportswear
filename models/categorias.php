<?php
require_once __DIR__ . '/../database/conection.php';

function getCategoriasModel() {
  global $conn;
  $query = "SELECT * FROM categorias ORDER BY id DESC";
  $result = $conn->query($query);
  $items = [];
  while ($row = $result->fetch_assoc()) {
    $items[] = $row;
  }
  return $items;
}

function getCategoriaByIdModel($id) {
  global $conn;
  $query = "SELECT * FROM categorias WHERE id = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_assoc();
}

function crearCategoriaModel($nombre, $descripcion = '') {
  global $conn;
  $query = "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $nombre, $descripcion);
  if ($stmt->execute()) {
    return ["success" => true, "id" => $conn->insert_id];
  }
  return false;
}

function actualizarCategoriaModel($id, $nombre, $descripcion = '') {
  global $conn;
  $query = "UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssi", $nombre, $descripcion, $id);
  return $stmt->execute();
}

function borrarCategoriaModel($id) {
  global $conn;
  $query = "DELETE FROM categorias WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  return $stmt->execute();
}

?>
