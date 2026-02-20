<?php

require_once "../../utils/response.php";
require_once "../../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$query = "
SELECT Drive.id,
       Drive.title,
       Drive.description,
       Drive.minCgpa,
       Drive.maxBacklogs,
       Drive.status,
       Company.name AS companyName
FROM Drive
JOIN Company ON Drive.companyId = Company.id
WHERE Drive.status='OPEN'
ORDER BY Drive.createdAt DESC
";

$result = $conn->query($query);

$drives = [];

while ($row = $result->fetch_assoc()) {
    $drives[] = $row;
}

jsonResponse(true, "Open Drives", $drives);