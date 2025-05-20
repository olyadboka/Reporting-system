<?php
session_start();
include "dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION["user_id"])) {
        die("User not logged in.");
    }

    $user_id = $_SESSION["user_id"];
    $category = mysqli_real_escape_string($con, $_POST["reportType"]);
    $reportDescription = mysqli_real_escape_string($con, $_POST["description"]);
    $specificIssue = mysqli_real_escape_string($con, $_POST["SpecificIssue"]);
    $location = "HERMATA MENTINA";
    $created_at = date("Y-m-d H:i:s");
    $count = 0;
    $priority = "medium";
    $status = "Pending";

    $image1 = isset($_FILES["image1"]["tmp_name"]) ? file_get_contents($_FILES["image1"]["tmp_name"]) : null;
    $image2 = isset($_FILES["image2"]["tmp_name"]) ? file_get_contents($_FILES["image2"]["tmp_name"]) : null;
    $image3 = isset($_FILES["image3"]["tmp_name"]) ? file_get_contents($_FILES["image3"]["tmp_name"]) : null;
    $image4 = isset($_FILES["image4"]["tmp_name"]) ? file_get_contents($_FILES["image4"]["tmp_name"]) : null;

    $sql = "INSERT INTO reports (
        user_id, category, description, specific_issue, location, 
        image_url_1, image_url_2, image_url_3, image_url_4,
        status, priority, created_at, count
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    // Correct bind parameters: i = int, s = string, b = blob
    mysqli_stmt_bind_param(
        $stmt,
        "isssssssssssi",
        $user_id, $category, $reportDescription, $specificIssue, $location,
        $image1, $image2, $image3, $image4,
        $status, $priority, $created_at, $count
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "Report submitted successfully.";
    } else {
        echo "Error submitting report: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}