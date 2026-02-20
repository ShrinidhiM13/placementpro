<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("ALUMNI");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['startTime'], $data['endTime'])) {
    jsonResponse(false, "Start and End time required", null, 400);
}

/* Get Alumni ID */
$alumni = $conn->query("SELECT id FROM Alumni WHERE userId={$user['id']}")->fetch_assoc();

if (!$alumni) {
    jsonResponse(false, "Alumni profile not found", null, 404);
}

$start = $conn->real_escape_string($data['startTime']);
$end = $conn->real_escape_string($data['endTime']);

$conn->query("
INSERT INTO MentorSlot (alumniId, startTime, endTime)
VALUES ({$alumni['id']}, '$start', '$end')
");

jsonResponse(true, "Mentor Slot Created");