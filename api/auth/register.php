<?php

require_once "../../config/database.php";
require_once "../../utils/response.php";

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['email'], $data['password'], $data['role'])) {
    jsonResponse(false, "All fields required", null, 400);
}

$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$role = $conn->real_escape_string($data['role']);

$allowedRoles = ['STUDENT','ALUMNI','TPO'];

if (!in_array($role, $allowedRoles)) {
    jsonResponse(false, "Invalid role", null, 400);
}

$check = $conn->query("SELECT id FROM User WHERE email='$email'");
if ($check->num_rows > 0) {
    jsonResponse(false, "Email already exists", null, 400);
}

$conn->query("
INSERT INTO User (name,email,passwordHash,role,updatedAt)
VALUES ('$name','$email','$password','$role',NOW())
");

jsonResponse(true, "User Registered Successfully");