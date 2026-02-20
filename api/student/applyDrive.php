<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

try {

    // GET STUDENT USING userId DIRECTLY
    $studentQuery = "SELECT * FROM Student WHERE userId = {$user['id']} LIMIT 1";
    $studentResult = $conn->query($studentQuery);

    if (!$studentResult || $studentResult->num_rows == 0) {
        jsonResponse(false, "Student not found", null, 404);
    }

    $student = $studentResult->fetch_assoc();
    $studentId = $student['id'];

    // SIMPLE INSERT ONLY
    $insert = "
        INSERT INTO Application (studentId, driveId, status, appliedAt)
        VALUES ($studentId, $driveId, 'APPLIED', NOW())
    ";

    if (!$conn->query($insert)) {
        die("MYSQL ERROR: " . $conn->error);
    }

    jsonResponse(true, "Applied Successfully");

} catch (Throwable $e) {
    die("PHP ERROR: " . $e->getMessage());
}