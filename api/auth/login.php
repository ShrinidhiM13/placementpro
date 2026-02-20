<?php

require_once "../../config/database.php";
require_once "../../utils/response.php";

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    jsonResponse(false, "Email and Password required", null, 400);
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password'];

$query = "SELECT * FROM User WHERE email='$email' AND isActive=1 LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    jsonResponse(false, "Invalid Credentials", null, 401);
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['passwordHash'])) {
    jsonResponse(false, "Invalid Credentials", null, 401);
}

/* Generate Token */
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+7 days"));

$insertSession = "INSERT INTO Session (token, userId, expiresAt)
                  VALUES ('$token', {$user['id']}, '$expiry')";

$conn->query($insertSession);

jsonResponse(true, "Login Successful", [
    "token" => $token,
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "role" => $user['role']
    ]
]);