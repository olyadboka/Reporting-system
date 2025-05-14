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
   
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['report_id'], $data['action'], $data['staff_name'])) {
        $report_id = $conn->real_escape_string($data['report_id']);
        $action = $conn->real_escape_string($data['action']);
        $staff_name = $conn->real_escape_string($data['staff_name']);

        // Determine the new status based on the action
        $status = '';
        if ($action === 'accept') {
            $status = 'Accepted';
        } elseif ($action === 'reject') {
            $status = 'Rejected';
        }

        if ($status) {
            // Update the status and handled_by columns in the database
            $sql = "UPDATE reports SET status = ?, handled_by = ? WHERE report_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $staff_name, $report_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => "Report #$report_id has been $status."]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }

    exit();
}

$conn->close();
?>