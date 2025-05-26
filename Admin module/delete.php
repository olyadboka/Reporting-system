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

echo $id = $_GET['id'];


$stm = $db->prepare("DELETE FROM users WHERE uid='$id'");
$stm->execute();
echo "DElete success";
header('location:users.php?success=delete success message&status=1');
