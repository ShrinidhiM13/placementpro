<?php

require_once "../../utils/response.php";
require_once "../../config/database.php";

$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    jsonResponse(false, "Unauthorized", null, 401);
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

$db = new Database();
$conn = $db->getConnection();

$conn->query("DELETE FROM Session WHERE token='$token'");

jsonResponse(true, "Logged out successfully");