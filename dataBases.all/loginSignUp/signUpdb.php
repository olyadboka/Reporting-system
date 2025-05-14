<?php
session_start();
include "../dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $houseNumber = trim($_POST["house-number"]);
    $idNumber = trim($_POST["id-no"]);
    $gender = trim($_POST["gender"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["create-password"]);
    $confirmPassword = trim($_POST["confirm-password"]);
    $kebele = trim($_POST["kebele"]);
    $phoneNumber = trim($_POST["phone-number"]);
    $role ="user";
    $created_at = date('Y-m-d H:i:s');
    $status = "active";

    if (
        $name == "" || $houseNumber == "" || $idNumber == "" || $gender == "" ||
        $email == "" || $password == "" || $confirmPassword == "" || $kebele == "" || $phoneNumber == ""
    ) {
        echo "All fields are required!";
        exit;
    }

    
    if (is_numeric($name) || strlen($name) < 3) {
        echo "Name must be at least 3 characters and not a number!";
        exit;
    }

    if ($password != $confirmPassword) {
        echo "Password and confirm password do not match!";
        exit;
    }
if(!preg_match("/^[0-9]{10}$/", $phoneNumber)){
  echo "Phone number must be 10 digists!";
  exit;
}
if(!filter_var($email, FILTER_FLAG_EMAIL_UNICODE)){
  echo 'Email is not valid!';
  exit;
}
    $sqll = "SELECT residence_id FROM residents WHERE residence_id = '$idNumber'";
    $result = mysqli_query($con, $sqll);
    if (mysqli_num_rows($result) == 0) {
        echo "Resident with this ID number does not exist!";
        exit;
    }

    $sql2 = "INSERT INTO users (full_name, email, password, phone, role, created_at)
             VALUES ('$name', '$email', '$password', '$phoneNumber', 'user', '$created_at')";
    $result2 = mysqli_query($con, $sql2);

    if ($result2) {
      $_SESSION['SUCCESS'] = "user created successfully";
        header("Location: ../login/login.php");
        exit;
    } else {
       $_SESSION['UNSUCCESS'] = "user not created";
        echo "Error: " . mysqli_error($con);
        header("Location: ../login/Signup.php");
        exit;
    }
}