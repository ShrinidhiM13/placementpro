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

/* ==============================
   FIXED MESSAGE
============================== */

$title = "New Placement Drive Available";
$message = "A new placement drive is available. Please login to PlacementPro and apply as soon as possible.";

/* ==============================
   GET ALL ELIGIBLE STUDENTS
============================== */

$query = "
SELECT User.id, User.email, User.name
FROM Student
JOIN User ON Student.userId = User.id
WHERE Student.isPlaced = 0
";

$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
    jsonResponse(false, "No eligible students found");
    exit;
}

/* ==============================
   SETUP GMAIL SMTP
============================== */

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'shrinidhimbrazil@gmail.com';
    $mail->Password   = 'ykpm naxb ugwr lwhv'; // 🔴 Replace with new app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->isHTML(true);
    $mail->Subject = $title;

    $successCount = 0;

    /* ==============================
       LOOP THROUGH STUDENTS
    ============================== */

    while ($student = $result->fetch_assoc()) {

        // 1️⃣ Insert notification into DB
        $insertQuery = "
            INSERT INTO Notification (userId, title, message)
            VALUES ({$student['id']}, '$title', '$message')
        ";

        $conn->query($insertQuery);

        // 2️⃣ Send email
        $mail->clearAddresses(); // IMPORTANT
        $mail->setFrom('shrinidhimbrazil@gmail.com', 'PlacementPro');
        $mail->addAddress($student['email'], $student['name']);

        $mail->Body = "
            <h3>PlacementPro Notification</h3>
            <p>$message</p>
            <p><b>Login Now:</b> http://localhost/placementpro/public/</p>
        ";

        $mail->send();
        $successCount++;
    }

    jsonResponse(true, "Successfully sent $successCount emails and notifications.");

} catch (Exception $e) {
    jsonResponse(false, "Mail Error: " . $mail->ErrorInfo);
}