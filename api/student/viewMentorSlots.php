<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$query = "
SELECT MentorSlot.id,
       MentorSlot.startTime,
       MentorSlot.endTime,
       User.name AS alumniName,
       Company.name AS companyName
FROM MentorSlot
JOIN Alumni ON MentorSlot.alumniId = Alumni.id
JOIN User ON Alumni.userId = User.id
JOIN Company ON Alumni.companyId = Company.id
WHERE MentorSlot.isBooked=0
ORDER BY MentorSlot.startTime ASC
";

$result = $conn->query($query);

$slots = [];

while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

jsonResponse(true, "Available Mentor Slots", $slots);