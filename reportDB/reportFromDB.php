<?php
session_start();
include "dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure session user ID is set
    $_SESSION["user_id"] = 1; // Replace with actual user authentication logic
    if (!isset($_SESSION["user_id"])) {
        die("User not logged in.");
    }

    // Collect form data
    $user_id = $_SESSION["user_id"];
    $category = mysqli_real_escape_string($con, $_POST["reportType"]);
    $reportDescription = mysqli_real_escape_string($con, $_POST["description"]);
    $specificIssue = mysqli_real_escape_string($con, $_POST["SpecificIssue"]);
    $location = mysqli_real_escape_string($con, $_POST["reporter_address"]);
    $created_at = date("Y-m-d H:i:s");
    $count = 0;
    $priority = "medium";
    $status = "Under Review";

    // Allowed file types and size limit
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB

    // Handle image uploads
    if (isset($_FILES["images"]) && count($_FILES["images"]["name"]) > 0) {
        $totalFiles = count($_FILES["images"]["name"]);

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = basename($_FILES["images"]["name"][$i]);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                echo "File {$fileName} is not a valid image type. Only JPG, JPEG, PNG, and GIF are allowed.<br>";
                continue;
            }

            // Validate file size
            if ($_FILES["images"]["size"][$i] > $maxFileSize) {
                echo "File {$fileName} exceeds the maximum size of 5 MB.<br>";
                continue;
            }

            // Read the file content as binary data
            $imageData = file_get_contents($_FILES["images"]["tmp_name"][$i]);

            // Insert the report and image into the database
            $sql = "INSERT INTO reports (user_id, category, description, specific_issue, location, image_url, status, priority, created_at, count) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "issssssssi", $user_id, $category, $reportDescription, $specificIssue, $location, $imageData, $status, $priority, $created_at, $count);

            if (mysqli_stmt_execute($stmt)) {
                echo "File {$fileName} has been uploaded and stored in the database successfully.<br>";
            } else {
                echo "Error storing file {$fileName} in the database: " . mysqli_error($con) . "<br>";
            }
        }
    } else {
        echo "No images were uploaded.";
    }
}