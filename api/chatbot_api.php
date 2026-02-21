<?php
header("Content-Type: application/json");

// ===============================
// DIRECT DATABASE CONNECTION
// ===============================
$host = "193.203.184.211";
$db_name = "u508697926_form_db";
$username = "u508697926_shri";
$password = "J+8kAjqP?xDa%Jp";

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "reply" => "Database connection failed."
    ]);
    exit;
}

// ===============================
// GET USER ID FROM FRONTEND
// ===============================
$userId = isset($_GET['studentId']) ? intval($_GET['studentId']) : 0;
$action = $_GET['action'] ?? '';

if ($userId == 0) {
    echo json_encode([
        "success" => false,
        "reply" => "Invalid user ID."
    ]);
    exit;
}

// ===============================
// CONVERT userId → Student.id
// ===============================
$studentQuery = $conn->query("
    SELECT id 
    FROM Student 
    WHERE userId = $userId 
    LIMIT 1
");

if (!$studentQuery || $studentQuery->num_rows == 0) {
    echo json_encode([
        "success" => true,
        "reply" => "Student record not found."
    ]);
    exit;
}

$studentRow = $studentQuery->fetch_assoc();
$studentId = $studentRow['id'];

$response = "";

// ===============================
// SWITCH ACTIONS
// ===============================
switch ($action) {

    case "interview_date":

        $sql = "
            SELECT d.title, i.startTime, i.room
            FROM StudentInterview si
            JOIN InterviewSlot i ON si.slotId = i.id
            JOIN Drive d ON si.driveId = d.id
            WHERE si.studentId = $studentId
            LIMIT 1
        ";

        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $response = "Your interview for " . $row['title'] . " is on "
                . date("d M Y h:i A", strtotime($row['startTime']))
                . " at " . $row['room'] . ".";
        } else {
            $response = "No interview scheduled yet.";
        }

        break;

    case "applied_jobs":

        $sql = "
            SELECT d.title
            FROM Application a
            JOIN Drive d ON a.driveId = d.id
            WHERE a.studentId = $studentId
        ";

        $result = $conn->query($sql);
        $jobs = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $jobs[] = $row['title'];
            }
        }

        $response = count($jobs) > 0
            ? "You have applied for: " . implode(", ", $jobs)
            : "You have not applied for any jobs.";

        break;

    case "application_status":

        $sql = "
            SELECT d.title, a.status
            FROM Application a
            JOIN Drive d ON a.driveId = d.id
            WHERE a.studentId = $studentId
            LIMIT 1
        ";

        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $response = "Your status for " . $row['title'] . " is " . $row['status'] . ".";
        } else {
            $response = "No application found.";
        }

        break;

    case "placement_status":

        $result = $conn->query("
            SELECT placementStatus 
            FROM Student
            WHERE id = $studentId
        ");

        if ($result && $row = $result->fetch_assoc()) {
            $response = $row['placementStatus'] === "PLACED"
                ? "You are placed."
                : "You are currently unplaced.";
        } else {
            $response = "Unable to fetch placement status.";
        }

        break;

    case "active_drives":

        $result = $conn->query("
            SELECT title 
            FROM Drive 
            WHERE status = 'OPEN'
        ");

        $drives = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $drives[] = $row['title'];
            }
        }

        $response = count($drives) > 0
            ? "Active Drives: " . implode(", ", $drives)
            : "No active drives currently.";

        break;

    case "placement_stats":

        $stats = $conn->query("
            SELECT 
            (SELECT COUNT(*) FROM Student) totalStudents,
            (SELECT COUNT(*) FROM Student WHERE placementStatus='PLACED') placedStudents
        ");

        if ($stats) {
            $data = $stats->fetch_assoc();
            $percent = $data['totalStudents'] > 0
                ? round(($data['placedStudents'] / $data['totalStudents']) * 100, 2)
                : 0;

            $response = "Placement Percentage: " . $percent . "%";
        } else {
            $response = "Unable to calculate statistics.";
        }

        break;

    default:
        $response = "Invalid option selected.";
        break;
}

// ===============================
// RETURN JSON
// ===============================
echo json_encode([
    "success" => true,
    "reply" => $response
]);

$conn->close();
?>