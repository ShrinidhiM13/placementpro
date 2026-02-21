<?php
header("Content-Type: application/json");

// ✅ Use same DB config
require_once("../config/db.php");

$studentId = $_GET['studentId'] ?? 1;
$action = $_GET['action'] ?? '';

$response = "";

switch($action) {

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

        if($result && $row = $result->fetch_assoc()){
            $response = "Your interview for ".$row['title']." is on "
                .date("d M Y h:i A", strtotime($row['startTime']))
                ." at ".$row['room'].".";
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
        while($row = $result->fetch_assoc()){
            $jobs[] = $row['title'];
        }

        $response = count($jobs) > 0
            ? "You have applied for: ".implode(", ", $jobs)
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

        if($row = $result->fetch_assoc()){
            $response = "Your status for ".$row['title']." is ".$row['status'].".";
        } else {
            $response = "No application found.";
        }
    break;

    case "placement_status":
        $result = $conn->query("
            SELECT placementStatus FROM Student
            WHERE id = $studentId
        ");

        $row = $result->fetch_assoc();

        $response = $row['placementStatus']=="PLACED"
            ? "Congratulations 🎉 You are placed!"
            : "You are currently unplaced.";
    break;

    case "active_drives":
        $result = $conn->query("
            SELECT title FROM Drive WHERE status='OPEN'
        ");

        $drives=[];
        while($row=$result->fetch_assoc()){
            $drives[]=$row['title'];
        }

        $response = count($drives)>0
            ? "Active Drives: ".implode(", ",$drives)
            : "No active drives currently.";
    break;

    case "placement_stats":
        $stats = $conn->query("
            SELECT 
            (SELECT COUNT(*) FROM Student) totalStudents,
            (SELECT COUNT(*) FROM Student WHERE placementStatus='PLACED') placedStudents
        ")->fetch_assoc();

        $percent = $stats['totalStudents']>0
            ? round(($stats['placedStudents']/$stats['totalStudents'])*100,2)
            : 0;

        $response = "Placement Percentage: ".$percent."%";
    break;

    default:
        $response = "Invalid option selected.";
}

echo json_encode([
    "success"=>true,
    "reply"=>$response
]);

$conn->close();
?>