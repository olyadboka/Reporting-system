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
    <title>users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php
    include 'admin_dashboard.php'

    
    ?>
<section class="users">
            <h2>Manage Users</h2>
            <div id="user-list"></div>
            <?php
        $q = "SELECT * FROM `users`";
        $stm = $db->prepare($q);
        $stm->execute();
        $res = $stm->fetchAll();
        // var_dump($res);


        ?>

        <table class="content-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>role</th>
                    <th></th>
                    <th></th>
                   
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($res as $i => $user) {


                ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><a href="edit.php?id=<?= $user['id'] ?>"><button>Edit</button></a></td>
                        <td><a href="delete.php?id=<?= $user['id'] ?>"><button>delete</button></a></td>
                     
                        
                       
                        
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
        </section>

        
    
</body>
</html>






