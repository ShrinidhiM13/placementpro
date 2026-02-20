<?php
require_once("../../config/middleware.php");

if($method !== "DELETE") {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Invalid method"]));
}

$input = json_decode(file_get_contents("php://input"), true);
$applicationId = $input['applicationId'] ?? null;

if(!$applicationId) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Application ID required"]));
}

$studentId = $userId; // From middleware

// Verify student owns this application
$appQuery = "SELECT a.id FROM applications a 
             JOIN students s ON a.student_id = s.id 
             WHERE a.id = $applicationId AND s.user_id = $studentId";
$appResult = $conn->query($appQuery);

if(!$appResult || $appResult->num_rows == 0) {
    http_response_code(403);
    die(json_encode(["success" => false, "message" => "Not authorized"]));
}

// Delete application status records first
$deleteStatusQuery = "DELETE FROM application_status WHERE application_id = $applicationId";
$conn->query($deleteStatusQuery);

// Delete application
$deleteQuery = "DELETE FROM applications WHERE id = $applicationId";

if($conn->query($deleteQuery)) {
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Application deleted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error deleting application: " . $conn->error]);
}
?>
