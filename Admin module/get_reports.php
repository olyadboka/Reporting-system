<?php
$host = "localhost";
$db = "report_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
try {
    $db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); 
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php
    include 'admin_dashboard.php'
    
    
    
    ?>
    <section id="reports">
        <h1>Reports</h1>
        <div id="report-list">
        <?php
        $q = "SELECT * FROM `reports`";
        $stm = $db->prepare($q);
        $stm->execute();
        $res = $stm->fetchAll();
        // var_dump($res);


        ?>
            
            <table>
                <thead>
                    <tr>
                        <th></th>
                       
                        <th>Title</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="report-table-body">
                   <?php
                   foreach($res as $i => $user){
                    ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        
                        <td><?= $user['title'] ?></td>
        
                        <td><?= $user['date'] ?></td>
                        <td><?= $user['description'] ?></td>
                        

                    </tr>
                   <?php
                   }
                    
                    ?>
                     
                </tbody>
            </table>
        </div>


        </div>
    </section>
    <script src="report.js"></script>
</body>
</html>