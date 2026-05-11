<?php
require_once __DIR__ . '/../database/connection.php';

global $conn;


function getUsersModel() {
  global $conn;

  $query = "SELECT * FROM users";
  $result = $conn->query($query);

  if (!$result) {
    return false;
  }

  $users = [];

  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }

  return $users;
}


function getUserByIdModel($id) {
  global $conn;

  $query = "SELECT * FROM users WHERE id = ?";
  $stmt = $conn->prepare($query);

  $stmt->bind_param("i", $id);
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    return null;
  }

  return $result->fetch_assoc();
}

function createUserModel($name, $email, $password, $role = 'user') {
  global $conn;

  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($query);

  $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

  if ($stmt->execute()) {
    return [
      "success" => true,
      "id" => $conn->insert_id
    ];
  }

  return false;
}


function updateUserModel($id, $name, $email) {
  global $conn;

  $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
  $stmt = $conn->prepare($query);

  $stmt->bind_param("ssi", $name, $email, $id);

  return $stmt->execute();
}


function deleteUserModel($id) {
  global $conn;

  $query = "DELETE FROM users WHERE id = ?";
  $stmt = $conn->prepare($query);

  $stmt->bind_param("i", $id);

  return $stmt->execute();
}