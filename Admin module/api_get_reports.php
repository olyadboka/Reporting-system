<?php
$host = "localhost";
$db = "report_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$sql = "SELECT id, title, date FROM reports";
$result = $conn->query($sql);

$reports = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

// Just echo the JSON without setting content-type
echo json_encode($reports);
?>
