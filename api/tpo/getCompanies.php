<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../config/database.php";
require_once "../../utils/response.php";

authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT id, name FROM Company");

$companies = [];

while ($row = $result->fetch_assoc()) {
    $companies[] = $row;
}

jsonResponse(true, "Companies fetched", $companies);