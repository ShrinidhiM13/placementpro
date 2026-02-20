<?php
header("Content-Type: application/json");

echo json_encode([
    "getallheaders" => getallheaders(),
    "_SERVER_AUTH" => $_SERVER['HTTP_AUTHORIZATION'] ?? null
]);