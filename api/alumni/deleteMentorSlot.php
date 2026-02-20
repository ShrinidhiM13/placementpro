<?php
require_once("../../config/middleware.php");

if($method !== "DELETE") {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Invalid method"]));
}

$input = json_decode(file_get_contents("php://input"), true);
$slotId = $input['slotId'] ?? null;

if(!$slotId) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Slot ID required"]));
}

$alumniId = $userId; // From middleware

$query = "DELETE FROM mentor_slots WHERE id = $slotId 
          AND alumni_id IN (
              SELECT id FROM alumni WHERE user_id = $alumniId
          )";

if($conn->query($query)) {
    if($conn->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Mentor slot deleted successfully"]);
    } else {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Slot not found or not authorized"]);
    }
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}
?>
