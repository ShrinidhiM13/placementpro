<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../config/database.php";
require_once "../../utils/response.php";

authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT id, name FROM Branch");

$branches = [];

while ($row = $result->fetch_assoc()) {
    $branches[] = $row;
}

jsonResponse(true, "Branches fetched", $branches);