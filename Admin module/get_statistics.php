

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "report_management";

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$statistics = [];


$query = "SELECT COUNT(*) as total_reports FROM reports";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$statistics['total_reports'] = $row['total_reports'];


$query = "SELECT COUNT(*) as total_users FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$statistics['total_users'] = $row['total_users'];


$query = "SELECT COUNT(*) as reports_this_month FROM reports WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
$stmt = $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$statistics['reports_this_month'] = $row['reports_this_month'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistics</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php
    include 'admin_dashboard.php'

    ?>
    <section id="statistics">
        <h2>Statistics</h2>
        <table class="content-table">
            <thead>
                <tr>
                    <th>Total Reports</th>
                    <th>Total Users</th>
                    <th>Reports This Month</th>
                </tr>
            </thead>
            <tbody>
                <tr>
        <td><?=$statistics ['total_reports'] ?></td>
        <td><?= $statistics['total_users'] ?></td>
        <td><?= $statistics['reports_this_month'] ?></td>
                </tr>
            </tbody>
        </table>
    </section>
</body>
</html>

    



       
       

    





