<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$applicationId = intval($data['applicationId']);
$status = $conn->real_escape_string($data['status']);

$allowed = ['APPLIED','SHORTLISTED','SELECTED','REJECTED'];

if(!in_array($status, $allowed)){
    jsonResponse(false, "Invalid status");
}

$query = "
    UPDATE Application
    SET status='$status'
    WHERE id=$applicationId
";

if(!$conn->query($query)){
    jsonResponse(false, "Update failed");
}

jsonResponse(true, "Status updated successfully");