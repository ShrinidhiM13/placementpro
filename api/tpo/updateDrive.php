<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    jsonResponse(false, "Invalid request method", null, 405);
}

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['driveId'])) {
    jsonResponse(false, "Drive ID required", null, 400);
}

$driveId = intval($data['driveId']);

/* Check if drive exists */
$check = $conn->query("SELECT id FROM Drive WHERE id=$driveId");

if ($check->num_rows == 0) {
    jsonResponse(false, "Drive not found", null, 404);
}

/* Build dynamic update */
$fields = [];

if (isset($data['title'])) {
    $title = $conn->real_escape_string($data['title']);
    $fields[] = "title='$title'";
}

if (isset($data['description'])) {
    $desc = $conn->real_escape_string($data['description']);
    $fields[] = "description='$desc'";
}

if (isset($data['minCgpa'])) {
    $minCgpa = floatval($data['minCgpa']);
    $fields[] = "minCgpa=$minCgpa";
}

if (isset($data['maxBacklogs'])) {
    $maxBacklogs = intval($data['maxBacklogs']);
    $fields[] = "maxBacklogs=$maxBacklogs";
}

if (isset($data['status'])) {
    $allowedStatus = ['OPEN','CLOSED','COMPLETED'];

    if (!in_array($data['status'], $allowedStatus)) {
        jsonResponse(false, "Invalid status value", null, 400);
    }

    $status = $conn->real_escape_string($data['status']);
    $fields[] = "status='$status'";
}

if (empty($fields)) {
    jsonResponse(false, "No fields to update", null, 400);
}

$updateQuery = "UPDATE Drive SET " . implode(",", $fields) . " WHERE id=$driveId";

if (!$conn->query($updateQuery)) {
    jsonResponse(false, "Update failed", $conn->error, 500);
}

jsonResponse(true, "Drive Updated Successfully");