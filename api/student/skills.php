<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();
$studentId = $student['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $result = $conn->query("
        SELECT Skill.id, Skill.name
        FROM StudentSkill
        JOIN Skill ON Skill.id = StudentSkill.skillId
        WHERE StudentSkill.studentId=$studentId
    ");

    $skills = [];

    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }

    jsonResponse(true, "Your Skills", $skills);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);
    $skillId = intval($data['skillId']);

    $conn->query("
        INSERT INTO StudentSkill (studentId, skillId)
        VALUES ($studentId, $skillId)
    ");

    jsonResponse(true, "Skill Added");
}