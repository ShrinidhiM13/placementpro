<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$query = "
SELECT JobPost.id,
       JobPost.title,
       JobPost.description,
       JobPost.createdAt,
       User.name AS alumniName,
       User.email AS alumniEmail,
       Company.name AS companyName
FROM JobPost
JOIN Alumni ON JobPost.alumniId = Alumni.id
JOIN User ON Alumni.userId = User.id
JOIN Company ON Alumni.companyId = Company.id
ORDER BY JobPost.createdAt DESC
";

$result = $conn->query($query);

$jobs = [];

while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

jsonResponse(true, "Available Job Posts", $jobs);