<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

/*
|--------------------------------------------------------------------------
| Authenticate (Allow Both TPO & STUDENT)
|--------------------------------------------------------------------------
*/

$user = authenticate(); 
// assuming your middleware returns role inside $user['role']

$db = new Database();
$conn = $db->getConnection();

$role = $user['role'];

$whereCondition = "d.status = 'OPEN'";

/*
|--------------------------------------------------------------------------
| If STUDENT → Filter by branch
|--------------------------------------------------------------------------
*/

if ($role === "STUDENT") {

    $branchQuery = "
        SELECT branchId 
        FROM Student 
        WHERE userId = {$user['id']}
        LIMIT 1
    ";

    $branchResult = $conn->query($branchQuery);

    if (!$branchResult || $branchResult->num_rows === 0) {
        jsonResponse(false, "Student branch not found");
    }

    $student = $branchResult->fetch_assoc();
    $studentBranchId = intval($student['branchId']);

    $whereCondition .= " AND d.branchId = $studentBranchId";
}

/*
|--------------------------------------------------------------------------
| Fetch Drives
|--------------------------------------------------------------------------
*/

$query = "
SELECT 
    d.id,
    d.title,
    d.description,
    d.minCgpa,
    d.maxBacklogs,
    d.status,
    d.image,
    c.name AS companyName,
    b.name AS branchName
FROM Drive d
JOIN Company c ON d.companyId = c.id
JOIN Branch b ON d.branchId = b.id
WHERE $whereCondition
ORDER BY d.createdAt DESC
";

$result = $conn->query($query);

$drives = [];

while ($row = $result->fetch_assoc()) {
    $drives[] = $row;
}

jsonResponse(true, "Drives fetched successfully", $drives);