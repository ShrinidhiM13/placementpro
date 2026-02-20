<?php

require_once "../../middleware/authMiddleware.php";
require_once "../../utils/response.php";
require_once "../../config/database.php";

$user = authenticate("TPO");

$db = new Database();
$conn = $db->getConnection();

/* =============================
   VALIDATION
============================= */

if(
    !isset($_POST['driveId']) ||
    !isset($_POST['title']) ||
    !isset($_POST['description']) ||
    !isset($_POST['companyId']) ||
    !isset($_POST['branchId']) ||
    !isset($_POST['minCgpa']) ||
    !isset($_POST['maxBacklogs']) ||
    !isset($_POST['status'])
){
    jsonResponse(false,"All fields are required");
}

$driveId = intval($_POST['driveId']);
$title = $conn->real_escape_string($_POST['title']);
$description = $conn->real_escape_string($_POST['description']);
$companyId = intval($_POST['companyId']);
$branchId = intval($_POST['branchId']);
$minCgpa = floatval($_POST['minCgpa']);
$maxBacklogs = intval($_POST['maxBacklogs']);
$status = $conn->real_escape_string($_POST['status']);

/* =============================
   VALIDATE STATUS
============================= */

$allowedStatus = ['OPEN','CLOSED'];
if(!in_array($status, $allowedStatus)){
    jsonResponse(false,"Invalid status");
}

/* =============================
   VALIDATE COMPANY & BRANCH
============================= */

$checkCompany = $conn->query("SELECT id FROM Company WHERE id = $companyId");
if($checkCompany->num_rows === 0){
    jsonResponse(false,"Invalid company selected");
}

$checkBranch = $conn->query("SELECT id FROM Branch WHERE id = $branchId");
if($checkBranch->num_rows === 0){
    jsonResponse(false,"Invalid branch selected");
}

/* =============================
   IMAGE REUPLOAD
============================= */

$imageQuery = "";

if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){

    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        jsonResponse(false,"Only JPG, JPEG, PNG allowed");
    }

    if($_FILES['image']['size'] > 2 * 1024 * 1024){
        jsonResponse(false,"Image must be less than 2MB");
    }

    if(!is_dir("../../uploads/drives")){
        mkdir("../../uploads/drives", 0777, true);
    }

    $newName = "drive_" . time() . "_" . rand(100,999) . "." . $ext;
    $uploadPath = "../../uploads/drives/" . $newName;

    if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)){
        $imagePath = "uploads/drives/" . $newName;
        $imageQuery = ", image='$imagePath'";
    }
}

/* =============================
   UPDATE DRIVE
============================= */

$query = "
    UPDATE Drive 
    SET 
        companyId=$companyId,
        branchId=$branchId,
        title='$title',
        description='$description',
        minCgpa=$minCgpa,
        maxBacklogs=$maxBacklogs,
        status='$status'
        $imageQuery
    WHERE id=$driveId
";

if(!$conn->query($query)){
    jsonResponse(false,"Drive update failed");
}

jsonResponse(true,"Drive updated successfully");