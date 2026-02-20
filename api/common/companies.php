<?php

require_once "../../utils/response.php";
require_once "../../config/database.php";

$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("SELECT id, name FROM Company ORDER BY name ASC");

$companies = [];

while ($row = $result->fetch_assoc()) {
    $companies[] = $row;
}

jsonResponse(true, "Company List", $companies);