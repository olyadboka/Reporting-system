<?php
$host = "localhost";
$db = "report_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
try {
    $db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Optional: Enable error reporting
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Or handle the error in a more appropriate way
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background-color: rgb(95, 43, 226);

    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 95vh;
        flex-direction: column;
    }

    .form {
        background-color: rgb(255, 255, 255);
        padding: 15px 20px !important;
        font-family: Arial, Helvetica, sans-serif;
        border-radius: 5px;
        box-shadow: 0px 0px 15px 1px rgba(98, 97, 97, 0.664);
    }

    .row {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    .input-row {
        display: flex;
        gap: 10px;
    }

    .row label {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .form-control,
    .row input {
        outline: none;
        border: 1px solid rgb(173, 171, 171);
        height: 2rem;
        border-radius: 8px;
        /* width: 100%; */
        padding: 0 10px;
        font-size: 15px;

    }

    .accept {
        display: flex;
        justify-content: center;
        gap: 5px;
        color: rgb(123, 123, 123);
    }

    .form h2 {
        text-transform: capitalize;
        text-align: center;
        margin-bottom: 20px;
        font-size: 28px;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    footer {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;
        height: 35px;
    }

    .btn-sign {
        outline: none;
        border: none;
        width: 100%;
        margin: 20px 0;
        background-color: rgb(95, 43, 226);
        color: #fff;
        font-size: 19px;
        font-weight: bold;
        cursor: pointer;
        font-family: sans-serif;
        padding: 10px 0;
        border-radius: 5px;
    }

    .error {
        padding: 10px 15px;
        background-color: red;
        color: white;
        margin: 10px 0;
    }
</style>

<body>

    <?php
    
    $id = $_GET['id'];

    $stm = $db->prepare("SELECT * FROM users WHERE id='$id'");
    $stm->execute();
    $stm->fetchAll();
    if ($stm->rowCount() <= 0) {
        echo "<h1>No record found</h1>";
        return;
    }


    $stm = $db->prepare("SELECT * FROM users WHERE id='$id'");
    $stm->execute();
    $user = $stm->fetch(PDO::FETCH_ASSOC);



    ?>
    <div class="container">
        <form class="form" action="update.php" method="POST">
            <h2>manage users</h2>
            <input type="text" name="uid" id="" value="<?= $user['id'] ?>">
            <div class="input-row">
                <div class="row">
                    <label for="">name</label>
                    <input type="text" name="name" id="" placeholder="" value="<?= $user['name'] ?>">
                </div>
                
            </div>
            

            <div class="row">
                <label for="">Email</label>
                <input type="email" name="email" id="" value="<?= $user['email'] ?>">

</div>
<button class="btn-sign" type="submit">SignUp</button>





            
        </form>
    </div>
    
</body>

</html>