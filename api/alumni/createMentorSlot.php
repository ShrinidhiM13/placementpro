<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("ALUMNI");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['startTime'], $data['endTime'])) {
    jsonResponse(false, "Start and End time are required", null, 400);
}

$startTime = $conn->real_escape_string($data['startTime']);
$endTime = $conn->real_escape_string($data['endTime']);

// Get Alumni ID
$alumniResult = $conn->query("SELECT id FROM Alumni WHERE userId={$user['id']} LIMIT 1");

if (!$alumniResult || $alumniResult->num_rows == 0) {
    jsonResponse(false, "Alumni profile not found", null, 404);
}

$alumni = $alumniResult->fetch_assoc();
$alumniId = $alumni['id'];

// Check for overlapping slots
$overlapCheck = $conn->query("
    SELECT id FROM MentorSlot
    WHERE alumniId=$alumniId
    AND (
        ('$startTime' < endTime AND '$endTime' > startTime)
    )
");

if ($overlapCheck && $overlapCheck->num_rows > 0) {
    jsonResponse(false, "Time slot overlaps with existing slot", null, 400);
}

// Insert new slot
$query = "
    INSERT INTO MentorSlot (alumniId, startTime, endTime, isBooked)
    VALUES ($alumniId, '$startTime', '$endTime', 0)
";

if (!$conn->query($query)) {
    jsonResponse(false, "Failed to create mentor slot", $conn->error, 500);
}

jsonResponse(true, "Mentor slot created successfully");
