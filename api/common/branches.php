<?php

require_once "../../utils/response.php";
require_once "../../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT id, name FROM Branch ORDER BY name ASC");

$branches = [];

while ($row = $result->fetch_assoc()) {
    $branches[] = $row;
}

jsonResponse(true, "Branch List", $branches);