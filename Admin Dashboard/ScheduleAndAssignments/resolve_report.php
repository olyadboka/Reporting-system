<?php
session_start();
// Direct connection code (put this at top of resolve_report.php)
$con = mysqli_connect("localhost", "root", "", "hmreportsystem");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Simple validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['report_id'])) {
    die("Invalid request");
}

$report_id = (int)$_POST['report_id'];
$notes = trim($_POST['resolution_notes']);
$resolved_by = $_SESSION['username'] ?? 'Admin';

// Update report status
mysqli_query($con, "UPDATE reports SET status='resolved' WHERE report_id=$report_id");

// Save resolution notes
mysqli_query($con, 
    "INSERT INTO report_status_history (report_id, history) 
     VALUES ($report_id, '".mysqli_real_escape_string($con, $notes)."')");

echo "OK"; // Simple success response
?>