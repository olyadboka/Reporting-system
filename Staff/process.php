<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'];
    $action = $_POST['action'];

    $status = '';
    if ($action === 'accept') {
        $status = 'accepted';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } elseif ($action === 'forward') {
        $status = 'forwarded';
    }

    if ($status) {
        $sql = "UPDATE reports SET status = ? WHERE report_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            // Optional: Log success message
        } else {
            // Optional: Log error message
        }

        $stmt->close();
    }

    // Redirect back to the requesting page (e.g., staff.php)
    header("Location: staff.php");
    exit();
}

$conn->close();
?>