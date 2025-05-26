<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
   

    <section class="users">
        <?php
        $id = $_GET['id'];
        $q = "SELECT * FROM `users` WHERE uid='$id'";
        $stm = $db->prepare($q);
        $stm->execute();
        $user = $stm->fetch(PDO::FETCH_ASSOC);
        // var_dump($res);
        $i = 0;
        ?>


        <table class="content-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>id</th>
                    <th>name</th>
                    <th>email</th>
                    <th>role</th>
                    
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td><?= ++$i ?></td>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['role'] ?></td>
                    <td><a href="edit.php?id=<?= $user['uid'] ?>"><button>Edit</button></a></td>
                    <td><a href="delete.php?id=<?= $user['uid'] ?>"><button>delete</button></a></td>
                    <td><a href="?id=<?= $user['uid'] ?>"><button>View</button></a></td>
                </tr>


            </tbody>
        </table>
    </section>
</body>

</html>