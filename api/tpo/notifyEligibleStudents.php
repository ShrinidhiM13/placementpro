<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['studentId'], $data['message'])) {
    jsonResponse(false, "Student ID and Message required", null, 400);
}

$studentId = intval($data['studentId']);
$message = $conn->real_escape_string($data['message']);
$title = $conn->real_escape_string($data['title'] ?? 'Important Notification');

// Insert notification
$conn->query("
    INSERT INTO Notification (userId, title, message)
    VALUES ($studentId, '$title', '$message')
");

if ($conn->error) {
    jsonResponse(false, "Failed to send notification", $conn->error, 500);
}

jsonResponse(true, "Notification sent successfully");