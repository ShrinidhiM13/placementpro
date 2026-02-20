<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../utils/response.php";

function authenticate($requiredRole = null) {

    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        jsonResponse(false, "Unauthorized", null, 401);
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);

    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT User.* FROM Session
              JOIN User ON Session.userId = User.id
              WHERE Session.token='$token'
              AND Session.expiresAt > NOW()
              LIMIT 1";

    $result = $conn->query($query);

    if ($result->num_rows == 0) {
        jsonResponse(false, "Invalid or Expired Token", null, 401);
    }

    $user = $result->fetch_assoc();

    if ($requiredRole && $user['role'] != $requiredRole) {
        jsonResponse(false, "Access Denied", null, 403);
    }

    return $user;
}