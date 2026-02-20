<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

// Get all eligible students based on open drives
$query = "
    SELECT DISTINCT
        Student.id,
        Student.cgpa,
        Student.backlogCount,
        User.id as userId,
        User.name,
        User.email
    FROM Student
    JOIN User ON Student.userId = User.id
    WHERE Student.placementStatus = 'UNPLACED'
    ORDER BY Student.cgpa DESC
";

$result = $conn->query($query);

if (!$result) {
    jsonResponse(false, "Query failed", null, 500);
}

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

jsonResponse(true, "Eligible Students List", $students);