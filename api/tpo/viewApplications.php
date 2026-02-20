<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$query = "
    SELECT 
        a.id,
        u.name,
        u.email,
        d.title AS driveTitle,
        c.name AS companyName,
        a.status,
        a.appliedAt
    FROM Application a
    JOIN Student s ON a.studentId = s.id
    JOIN User u ON s.userId = u.id
    JOIN Drive d ON a.driveId = d.id
    JOIN Company c ON d.companyId = c.id
    ORDER BY a.appliedAt DESC
";

$result = $conn->query($query);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

jsonResponse(true, "Applications fetched", $data);