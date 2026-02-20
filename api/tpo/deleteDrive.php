<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);
$driveId = intval($data['driveId']);

if(!$driveId){
    jsonResponse(false, "Invalid drive id");
}

$conn->query("DELETE FROM Drive WHERE id=$driveId");

jsonResponse(true, "Drive deleted successfully");