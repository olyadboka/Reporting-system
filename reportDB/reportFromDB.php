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
    $count = 0;
    $priority = "medium";
    $status = "Under Review";

    // File uploads handling
  
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uploadedImages = [];
        $uploadDir = 'uploads/'; // Directory to store images
    
        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        // Handle uploaded images
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($key < 4 && is_uploaded_file($tmpName)) { // Limit to 4 images
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetFile = $uploadDir . uniqid() . '_' . $fileName;
    
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $uploadedImages[] = $targetFile;
                }
            }
        }
    
        // Store image paths in the database
        $imageUrls = json_encode($uploadedImages); // Use JSON to store multiple paths
        $sql = "INSERT INTO reports (image_url) VALUES (?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $imageUrls);
    
        if (mysqli_stmt_execute($stmt)) {
            echo "Report submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($con);
        }
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