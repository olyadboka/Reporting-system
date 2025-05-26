

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
</head>
<body>
<?php


echo $id = $_POST['id'];
echo $name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];


$stm = $db->prepare("UPDATE user SET fname='$name', email='$email',role='$role'  WHERE uid='$id'");
$stm->execute();
echo "success";
header('location:get_users.php?success=update successful ');
// header('location:get_users.php?success=update successful&status=1');
?>
    

</body>
</html>

