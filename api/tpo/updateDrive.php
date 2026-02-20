<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

$driveId = intval($_POST['driveId']);
$title = $conn->real_escape_string($_POST['title']);
$description = $conn->real_escape_string($_POST['description']);
$minCgpa = floatval($_POST['minCgpa']);
$maxBacklogs = intval($_POST['maxBacklogs']);
$status = $_POST['status'];

$imageQuery = "";

/* ===== IMAGE REUPLOAD ===== */
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){

    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        jsonResponse(false,"Only JPG, JPEG, PNG allowed");
    }

    $newName = "drive_" . time() . "_" . rand(100,999) . "." . $ext;
    $uploadPath = "../../uploads/drives/" . $newName;

    move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

    $imagePath = "uploads/drives/" . $newName;

    $imageQuery = ", image='$imagePath'";
}

$conn->query("
    UPDATE Drive 
    SET title='$title',
        description='$description',
        minCgpa=$minCgpa,
        maxBacklogs=$maxBacklogs,
        status='$status'
        $imageQuery
    WHERE id=$driveId
");

jsonResponse(true,"Drive updated successfully");