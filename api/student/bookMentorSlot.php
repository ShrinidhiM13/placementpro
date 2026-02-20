<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("STUDENT");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['slotId'])) {
    jsonResponse(false, "Slot ID required", null, 400);
}

$slotId = intval($data['slotId']);

/* Get Student */
$student = $conn->query("SELECT id FROM Student WHERE userId={$user['id']}")->fetch_assoc();

if (!$student) {
    jsonResponse(false, "Student profile not found", null, 404);
}

/* Check if already booked */
$check = $conn->query("
SELECT id FROM MentorBooking
WHERE slotId=$slotId
");

if ($check->num_rows > 0) {
    jsonResponse(false, "Slot already booked", null, 400);
}

/* Insert Booking */
$conn->query("
INSERT INTO MentorBooking (slotId, studentId)
VALUES ($slotId, {$student['id']})
");

/* Mark slot as booked */
$conn->query("
UPDATE MentorSlot
SET isBooked=1
WHERE id=$slotId
");

jsonResponse(true, "Mentor Slot Booked Successfully");