<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

/* Get Student */
$studentQuery = "SELECT * FROM Student WHERE userId={$user['id']} LIMIT 1";
$studentResult = $conn->query($studentQuery);

if ($studentResult->num_rows == 0) {
    jsonResponse(false, "Student Not Found", null, 404);
}

$student = $studentResult->fetch_assoc();

/* Fetch Applications */
$query = "
SELECT Application.*, Drive.title, Company.name AS companyName
FROM Application
JOIN Drive ON Application.driveId = Drive.id
JOIN Company ON Drive.companyId = Company.id
WHERE Application.studentId = {$student['id']}
ORDER BY Application.appliedAt DESC
";

$result = $conn->query($query);

$applications = [];
while ($row = $result->fetch_assoc()) {
    $applications[] = $row;
}

jsonResponse(true, "Application Status List", $applications);