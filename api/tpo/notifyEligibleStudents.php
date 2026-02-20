<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";
require_once "../../utils/PHPMailer/src/PHPMailer.php";
require_once "../../utils/PHPMailer/src/SMTP.php";
require_once "../../utils/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['message'])) {
    jsonResponse(false, "Message required", null, 400);
}

$message = $conn->real_escape_string($data['message']);
$title = "Placement Drive Notification";

/* Get all eligible students */
$query = "
SELECT User.id, User.email, User.name
FROM Student
JOIN User ON Student.userId = User.id
WHERE Student.isPlaced = 0
";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    jsonResponse(false, "No eligible students found");
}

/* Gmail Credentials (Use ENV in production) */
$gmailUser = "shrinidhimbrazil@gmail.com";
$gmailPass = "ykpm naxb ugwr lwhv"; // CHANGE THIS

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $gmailUser;
    $mail->Password   = $gmailPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom($gmailUser, "PlacementPro");

    while ($student = $result->fetch_assoc()) {

        // Insert notification in DB
        $conn->query("
            INSERT INTO Notification (userId, title, message)
            VALUES ({$student['id']}, '$title', '$message')
        ");

        $mail->addAddress($student['email'], $student['name']);
    }

    $mail->Subject = $title;
    $mail->Body    = $message;

    $mail->send();

    jsonResponse(true, "Notification sent to all eligible students");

} catch (Exception $e) {
    jsonResponse(false, "Mail Error: " . $mail->ErrorInfo);
}