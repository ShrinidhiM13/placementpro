<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();
$studentId = $student['id'];

// Get all applications for this student to filter interview slots for those drives
$query = "
SELECT 
    InterviewSlot.id,
    InterviewSlot.startTime,
    InterviewSlot.endTime,
    InterviewSlot.room,
    Drive.title AS driveTitle,
    Company.name AS companyName
FROM InterviewSlot
JOIN Drive ON InterviewSlot.driveId = Drive.id
JOIN Company ON Drive.companyId = Company.id
WHERE Drive.id IN (
    SELECT DISTINCT driveId FROM Application WHERE studentId=$studentId
)
AND InterviewSlot.isBooked=0
ORDER BY InterviewSlot.startTime ASC
";

$result = $conn->query($query);

$slots = [];

while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

jsonResponse(true, "Available Interview Slots", $slots);