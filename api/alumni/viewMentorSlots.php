<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("ALUMNI");

$db = new Database();
$conn = $db->getConnection();

$alumni = $conn->query("SELECT id FROM Alumni WHERE userId={$user['id']}")->fetch_assoc();

if (!$alumni) {
    jsonResponse(false, "Alumni profile not found", null, 404);
}

$query = "
SELECT 
    id,
    startTime,
    endTime,
    isBooked
FROM MentorSlot
WHERE alumniId={$alumni['id']}
ORDER BY startTime DESC
";

$result = $conn->query($query);

$slots = [];

while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

jsonResponse(true, "Your Mentor Slots", $slots);
