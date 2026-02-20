<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();
$studentId = $student['id'];

/* GET EDUCATION */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $result = $conn->query("
        SELECT id, degree, institute, year, marks
        FROM Education
        WHERE studentId=$studentId
        ORDER BY year DESC
    ");

    $education = [];

    while ($row = $result->fetch_assoc()) {
        $education[] = $row;
    }

    jsonResponse(true, "Education List", $education);
}

/* ADD EDUCATION */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $degree = $conn->real_escape_string($data['degree']);
    $institute = $conn->real_escape_string($data['institute']);
    $year = intval($data['year']);
    $marks = floatval($data['marks']);

    $conn->query("
        INSERT INTO Education (studentId, degree, institute, year, marks)
        VALUES ($studentId, '$degree', '$institute', $year, $marks)
    ");

    jsonResponse(true, "Education Added");
}

/* DELETE EDUCATION */
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"), true);
    $educationId = intval($data['educationId']);

    $conn->query("
        DELETE FROM Education
        WHERE id=$educationId AND studentId=$studentId
    ");

    jsonResponse(true, "Education Deleted");
}