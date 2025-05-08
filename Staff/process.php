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
        $status = 'Accepted';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } elseif ($action === 'forward') {
        $status = 'Forwarded';
    }

    if ($status) {
        $sql = "UPDATE reports SET status = ? WHERE report_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            echo "Report #$report_id has been $status.";
        } else {
            echo "Error updating report: " . $stmt->error;
        }

        $stmt->close();
    }

    // Redirect back to the staff page
    header("Location: staff.php");
    exit();
}

$conn->close();
?>