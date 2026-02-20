<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

/* Get Student */
$studentQuery = "SELECT * FROM Student WHERE userId={$user['id']} LIMIT 1";
$student = $conn->query($studentQuery)->fetch_assoc();

if (!$student) {
    jsonResponse(false, "Student Not Found", null, 404);
}

// Check if specific application ID is provided
if (isset($_GET['applicationId'])) {
    $appId = intval($_GET['applicationId']);
    
    $query = "
        SELECT 
            Application.id,
            Application.status,
            Application.appliedAt,
            Application.updatedAt,
            Drive.title AS driveTitle,
            Company.name AS companyName
        FROM Application
        JOIN Drive ON Application.driveId = Drive.id
        JOIN Company ON Drive.companyId = Company.id
        WHERE Application.id = $appId
        AND Application.studentId = {$student['id']}
    ";
    
    $result = $conn->query($query);
    
    if ($result->num_rows == 0) {
        jsonResponse(false, "Application Not Found", null, 404);
    }
    
    $app = $result->fetch_assoc();
    jsonResponse(true, "Application Status", $app);
} else {
    // Fetch All Applications
    $query = "
        SELECT 
            Application.id,
            Application.status,
            Application.appliedAt,
            Application.updatedAt,
            Drive.title AS driveTitle,
            Company.name AS companyName
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
}