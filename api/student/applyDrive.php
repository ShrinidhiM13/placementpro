<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['driveId'])) {
    jsonResponse(false, "Drive ID Required", null, 400);
}

$driveId = intval($data['driveId']);

/* Get Student Record */
$studentQuery = "SELECT * FROM Student WHERE userId={$user['id']} LIMIT 1";
$studentResult = $conn->query($studentQuery);

if ($studentResult->num_rows == 0) {
    jsonResponse(false, "Student Profile Not Found", null, 404);
}

$student = $studentResult->fetch_assoc();

/* Get Drive */
$driveQuery = "SELECT * FROM Drive WHERE id=$driveId AND status='OPEN'";
$driveResult = $conn->query($driveQuery);

if ($driveResult->num_rows == 0) {
    jsonResponse(false, "Drive Not Available", null, 404);
}

$drive = $driveResult->fetch_assoc();

/* Check Branch Eligibility */
$branchCheck = $conn->query("SELECT * FROM DriveBranch 
                             WHERE driveId=$driveId 
                             AND branchId={$student['branchId']}");

if ($branchCheck->num_rows == 0) {
    jsonResponse(false, "Not Eligible: Branch Not Allowed", null, 403);
}

/* Check CGPA & Backlogs */
if ($student['cgpa'] < $drive['minCgpa']) {
    jsonResponse(false, "Not Eligible: CGPA Criteria Not Met", null, 403);
}

if ($student['backlogCount'] > $drive['maxBacklogs']) {
    jsonResponse(false, "Not Eligible: Backlog Criteria Not Met", null, 403);
}

/* Prevent Duplicate Application */
$duplicateCheck = $conn->query("SELECT * FROM Application 
                                WHERE studentId={$student['id']} 
                                AND driveId=$driveId");

if ($duplicateCheck->num_rows > 0) {
    jsonResponse(false, "Already Applied", null, 400);
}

/* Apply */
$applyQuery = "INSERT INTO Application (studentId, driveId)
               VALUES ({$student['id']}, $driveId)";

if (!$conn->query($applyQuery)) {
    jsonResponse(false, "Application Failed", $conn->error, 500);
}

jsonResponse(true, "Applied Successfully", [
    "status" => "APPLIED"
]);