<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['driveId'])) {
    jsonResponse(false, "Drive ID Required", null, 400);
}

$driveId = intval($_GET['driveId']);

/* Get Drive Criteria */
$driveQuery = "SELECT * FROM Drive WHERE id=$driveId LIMIT 1";
$driveResult = $conn->query($driveQuery);

if ($driveResult->num_rows == 0) {
    jsonResponse(false, "Drive Not Found", null, 404);
}

$drive = $driveResult->fetch_assoc();

/* Get Eligible Branches */
$branchQuery = "SELECT branchId FROM DriveBranch WHERE driveId=$driveId";
$branchResult = $conn->query($branchQuery);

$branchIds = [];
while ($row = $branchResult->fetch_assoc()) {
    $branchIds[] = $row['branchId'];
}

$branchList = implode(",", $branchIds);

/* Criteria Engine Query */
$criteriaQuery = "
    SELECT Student.*, User.name, User.email
    FROM Student
    JOIN User ON Student.userId = User.id
    WHERE Student.cgpa >= {$drive['minCgpa']}
    AND Student.backlogCount <= {$drive['maxBacklogs']}
    AND Student.branchId IN ($branchList)
";

$result = $conn->query($criteriaQuery);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

jsonResponse(true, "Eligible Students Found", [
    "totalEligible" => count($students),
    "students" => $students
]);