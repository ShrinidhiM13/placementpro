<?php

require_once "../../utils/response.php";
require_once "../../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$query = "
SELECT 
    d.id,
    d.title,
    d.description,
    d.minCgpa,
    d.maxBacklogs,
    d.status,
    d.image,
    c.name AS companyName
FROM Drive d
JOIN Company c ON d.companyId = c.id
WHERE d.status='OPEN'
";

$result = $conn->query($query);

$drives = [];

while ($row = $result->fetch_assoc()) {
    $drives[] = $row;
}

jsonResponse(true, "Open Drives", $drives);