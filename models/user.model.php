<?php
require_once __DIR__ . '/../database/conection.php';

function getUsersModel() {
  global $conn;
  $query = "SELECT id, nombre, correo, creado, rol FROM usuario";
  $result = $conn->query($query);
  $usuario = [];
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $usuario[] = $row;
    }
  }
  return $usuario;
}

function getUserByIdModel($id) {
  global $conn;
  $query = "SELECT id, nombre, correo, creado, rol FROM usuario WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    return null;
  }
  return $result->fetch_assoc();
}

function getUserByEmailModel($email) {
  global $conn;
  $query = "SELECT id, nombre, correo, creado, rol FROM usuario WHERE correo = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    return null;
  }
  return $result->fetch_assoc();
}

function createUserModel($nombre, $correo, $contrasena, $rol) {
  global $conn;
  // NOTA: Como en tu base de datos pusiste varchar(8) para la contraseña, 
  // se guardará en texto plano porque un hash seguro toma 60 caracteres.
  $query = "INSERT INTO usuario (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssss", $nombre, $correo, $contrasena, $rol);
  
  if ($stmt->execute()) {
    return ["success" => true, "id" => $conn->insert_id];
  }
  return false;
}

function updateUserModel($id, $nombre, $correo, $rol) {
  global $conn;
  $query = "UPDATE usuario SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
  return $stmt->execute();
}

function deleteUserModel($id) {
  global $conn;
  $query = "DELETE FROM usuario WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  return $stmt->execute();
}