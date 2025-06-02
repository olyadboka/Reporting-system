<?php
session_start();
require_once '../dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = intval($_POST['report_id']);
    $resolution_notes = htmlspecialchars(trim($_POST['resolution_notes']));
    $resolved_by = $_SESSION['username'] ?? 'System';

    try {
        // Begin transaction
        mysqli_begin_transaction($con);

        // 1. Update report status
        $update_report = mysqli_prepare($con, "UPDATE reports SET status = 'resolved' WHERE report_id = ?");
        mysqli_stmt_bind_param($update_report, "i", $report_id);
        mysqli_stmt_execute($update_report);

        // 2. Add to status history
        $add_history = mysqli_prepare($con, 
            "INSERT INTO report_status_history (report_id, history, changed_by) 
             VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($add_history, "iss", $report_id, $resolution_notes, $resolved_by);
        mysqli_stmt_execute($add_history);

        // Commit transaction
        mysqli_commit($con);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        mysqli_rollback($con);
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}