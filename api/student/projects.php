<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();
$studentId = $student['id'];

/* GET PROJECTS */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $result = $conn->query("
        SELECT id, title, description, techStack
        FROM Project
        WHERE studentId=$studentId
        ORDER BY id DESC
    ");

    $projects = [];

    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    jsonResponse(true, "Project List", $projects);
}

/* ADD PROJECT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $title = $conn->real_escape_string($data['title']);
    $description = $conn->real_escape_string($data['description']);
    $techStack = $conn->real_escape_string($data['techStack']);

    $conn->query("
        INSERT INTO Project (studentId, title, description, techStack)
        VALUES ($studentId, '$title', '$description', '$techStack')
    ");

    jsonResponse(true, "Project Added");
}

/* DELETE PROJECT */
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"), true);
    $projectId = intval($data['projectId']);

    $conn->query("
        DELETE FROM Project
        WHERE id=$projectId AND studentId=$studentId
    ");

    jsonResponse(true, "Project Deleted");
}