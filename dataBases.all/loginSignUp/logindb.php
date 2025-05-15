<?php
session_start();
include "../dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idNumber = mysqli_real_escape_string($con, $_POST["id-no"]);
    $passwordInput = $_POST["password"];

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $idNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['UNSUCCESS'] = "Resident with this ID number does not exist!";
        header("Location: ../login/Signup.php");
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    if (password_verify($passwordInput, $row["password"])) {
        $_SESSION['SUCCESS'] = "Logged in successfully";
        $_SESSION["user_id"] = $idNumber;
        $_SESSION["role"] = $row["role"];
        $_SESSION["status"] = $row["status"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["phone"] = $row["phone"];
        $_SESSION["full_name"] = $row["full_name"];
        $_SESSION["image"] = $row["image"];


        
        if($row["role"] == "admin") {
            header("Location: ../Admin Dashboard/dashboardHome.php");
            exit;
        } else if ($row["role"] == "staff") {
            header("Location: ../Staff/staff.php");
            exit;
        } else if ($row["role"] == "user") {
            header("Location: ../Hermata home/index.php");
            exit;
        } else {
            $_SESSION['UNSUCCESS'] = "Unknown role!";
            header("Location: ../login/Signup.php");
            exit;
        }
    } else {
        $_SESSION['UNSUCCESS'] = "Invalid password!";
        header("Location: ../login/Signup.php");
        exit;
    }
}