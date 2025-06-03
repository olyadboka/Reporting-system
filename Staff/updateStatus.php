<?php
// Start session and set headers at the very top
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized');
}

// Only proceed for POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Method Not Allowed');
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

try {
    // Validate input
    if (!isset($_POST['report_id']) || !isset($_POST['status'])) {
        throw new Exception('Missing required fields');
    }

    $report_id = (int)$_POST['report_id'];
    $status = $_POST['status'];
    $allowed_statuses = ['approved', 'rejected', 'resolved'];

    if (!in_array($status, $allowed_statuses)) {
        throw new Exception('Invalid status value');
    }

    // Connect to database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get staff name
    $stmt = $conn->prepare("SELECT fname FROM residents WHERE residence_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $staff_name = $stmt->fetchColumn() ?: 'Unknown';

    // Update report
    $stmt = $conn->prepare("UPDATE reports SET status = ?, handled_by = ? WHERE report_id = ?");
    $stmt->execute([$status, $staff_name, $report_id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('No records updated - report may not exist');
    }

    // Redirect back to previous page after successful update
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();

} catch (Exception $e) {
    // You could handle errors more gracefully here
    die('Error: ' . $e->getMessage());
}
?>