<?php

/* ==============================
   ERROR REPORTING (TURN OFF IN PROD)
============================== */
error_reporting(E_ALL);
ini_set('display_errors', 0);

/* ==============================
   CORS HEADERS
============================== */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

/* Handle Preflight */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(["success" => true]);
    exit();
}

/* ==============================
   DATABASE CONNECTION
   ⚠️ CHANGE USERNAME + PASSWORD
============================== */
$host = "193.203.184.211";
$username = "u508697926_shri";
$password = "J+8kAjqP?xDa%Jp";
$database = "u508697926_form_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "error" => "Database connection failed",
        "details" => $conn->connect_error
    ]);
    exit();
}

/* ==============================
   SAFE QUERY FUNCTION
============================== */
function getSingleValue($conn, $query, $field = "total") {
    $result = $conn->query($query);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "error" => "Query failed",
            "details" => $conn->error
        ]);
        exit();
    }

    $row = $result->fetch_assoc();
    return isset($row[$field]) ? $row[$field] : 0;
}

/* ==============================
   ACTIVE DRIVES
============================== */
$activeDrives = getSingleValue(
    $conn,
    "SELECT COUNT(*) as total FROM Drive WHERE status='OPEN'"
);

/* ==============================
   TOTAL APPLICATIONS
============================== */
$applications = getSingleValue(
    $conn,
    "SELECT COUNT(*) as total FROM Application"
);

/* ==============================
   TOTAL STUDENTS
============================== */
$totalStudents = getSingleValue(
    $conn,
    "SELECT COUNT(*) as total FROM Student"
);

/* ==============================
   PLACED STUDENTS
============================== */
$placedStudents = getSingleValue(
    $conn,
    "SELECT COUNT(*) as total FROM Student WHERE placementStatus='PLACED'"
);

$unplacedStudents = $totalStudents - $placedStudents;

/* ==============================
   PLACEMENT %
============================== */
$placementPercentage = $totalStudents > 0
    ? round(($placedStudents / $totalStudents) * 100, 2)
    : 0;

/* ==============================
   PACKAGE STATS
============================== */
$packageQuery = "
    SELECT 
        IFNULL(MAX(package),0) as highest,
        IFNULL(AVG(package),0) as average
    FROM PlacementRecord
";

$packageResult = $conn->query($packageQuery);

if (!$packageResult) {
    echo json_encode([
        "success" => false,
        "error" => "Package query failed",
        "details" => $conn->error
    ]);
    exit();
}

$packageStats = $packageResult->fetch_assoc();

$highestPackage = (float)$packageStats['highest'];
$averagePackage = round((float)$packageStats['average'], 2);

/* ==============================
   FINAL RESPONSE
============================== */
echo json_encode([
    "success" => true,
    "activeDrives" => (int)$activeDrives,
    "applications" => (int)$applications,
    "totalStudents" => (int)$totalStudents,
    "placedStudents" => (int)$placedStudents,
    "unplacedStudents" => (int)$unplacedStudents,
    "placementPercentage" => $placementPercentage,
    "highestPackage" => $highestPackage,
    "averagePackage" => $averagePackage
]);

$conn->close();
exit();
?>