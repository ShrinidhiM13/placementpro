<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();
$studentId = $student['id'];

/* GET CERTIFICATIONS */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $result = $conn->query("
        SELECT id, name, issuer, year
        FROM Certification
        WHERE studentId=$studentId
        ORDER BY year DESC
    ");

    $certifications = [];

    while ($row = $result->fetch_assoc()) {
        $certifications[] = $row;
    }

    jsonResponse(true, "Certification List", $certifications);
}

/* ADD CERTIFICATION */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    $name = $conn->real_escape_string($data['name']);
    $issuer = $conn->real_escape_string($data['issuer']);
    $year = intval($data['year']);

    $conn->query("
        INSERT INTO Certification (studentId, name, issuer, year)
        VALUES ($studentId, '$name', '$issuer', $year)
    ");

    jsonResponse(true, "Certification Added");
}

/* DELETE CERTIFICATION */
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents("php://input"), true);
    $certId = intval($data['certId']);

    $conn->query("
        DELETE FROM Certification
        WHERE id=$certId AND studentId=$studentId
    ");

    jsonResponse(true, "Certification Deleted");
}