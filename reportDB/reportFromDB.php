<?php
session_start();
include "dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$_SESSION["user_id"]= 1;
    // Ensure session user ID is set
    if (!isset($_SESSION["user_id"])) {
        die("User not logged in.");
    }

    $user_id = $_SESSION["user_id"];
    $category = mysqli_real_escape_string($con, $_POST["reportType"]);
    $reportDescription = mysqli_real_escape_string($con, $_POST["description"]);
    $specificIssue = mysqli_real_escape_string($con, $_POST["SpecificIssue"]);
    $location = $_POST["reporter_address"];
    $created_at = date("Y-m-d H:i:s");
    $count = 1;
    $priority = "medium";
    $status = "Under Review";

    // File uploads handling
    $images = $videos = $files = null;
    if (!empty($_FILES['images']['tmp_name'])) {
        $images = file_get_contents($_FILES['images']['tmp_name']);
    }
    if (!empty($_FILES['videos']['tmp_name'])) {
        $videos = file_get_contents($_FILES['videos']['tmp_name']);
    }
    if (!empty($_FILES['file']['tmp_name'])) {
        $files = file_get_contents($_FILES['file']['tmp_name']);
    }


    $sql = "INSERT INTO reports (user_id, category, description, location, image_url, status, priority, created_at, count) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "issssssss", $user_id, $category, $reportDescription, $location, $images, $status, $priority, $created_at, $count);

    if (mysqli_stmt_execute($stmt)) {
        echo "Your report has been successfully recorded.";
        $_SESSION["report_Successful"] ="Your report is being recorded";
    } else {
        $_SESSION["report_UnSuccessful"] ="Error";
        echo "There was an issue: " . mysqli_error($con);
    }
}