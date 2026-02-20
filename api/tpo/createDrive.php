<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['companyId']) ||
    !isset($data['title']) ||
    !isset($data['minCgpa']) ||
    !isset($data['maxBacklogs']) ||
    !isset($data['branches'])
) {
    jsonResponse(false, "Missing Required Fields", null, 400);
}

$companyId = intval($data['companyId']);
$title = $conn->real_escape_string($data['title']);
$description = isset($data['description']) ? $conn->real_escape_string($data['description']) : "";
$minCgpa = floatval($data['minCgpa']);
$maxBacklogs = intval($data['maxBacklogs']);
$branches = $data['branches']; // array of branch IDs

/* Insert Drive */
$query = "INSERT INTO Drive (companyId, title, description, minCgpa, maxBacklogs)
          VALUES ($companyId, '$title', '$description', $minCgpa, $maxBacklogs)";

if (!$conn->query($query)) {
    jsonResponse(false, "Drive Creation Failed", $conn->error, 500);
}

$driveId = $conn->insert_id;

/* Insert Branch Mapping */
foreach ($branches as $branchId) {
    $conn->query("INSERT INTO DriveBranch (driveId, branchId)
                  VALUES ($driveId, " . intval($branchId) . ")");
}

jsonResponse(true, "Drive Created Successfully", [
    "driveId" => $driveId
]);