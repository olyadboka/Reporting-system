<?php
session_start();
include "../dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['UNSUCCESS'] = "Invalid CSRF token!";
        header("Location: ../../login/login.php");
        exit;
    }

    $idNumber = trim(mysqli_real_escape_string($con, $_POST["user_id"]));
    $passwordInput = trim($_POST["password"]);

    if (empty($idNumber) || empty($passwordInput)) {
        $_SESSION['UNSUCCESS'] = "ID and password are required!";
        header("Location: ../../login/login.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE kebele_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $idNumber);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['UNSUCCESS'] = "Resident with this ID number does not exist!";
        header("Location: ../../login/login.php");
        exit;
    }

    $row = mysqli_fetch_array($result);

$hashedPassword = password_hash($row["password"], PASSWORD_DEFAULT);

  
    if (password_verify($passwordInput, $hashedPassword)) {

        session_regenerate_id(true);

        $_SESSION['SUCCESS'] = "Logged in successfully";
        $_SESSION['user_id'] = $row["user_id"];
        $_SESSION['user_name'] = $row["full_name"];
        $_SESSION['user_email'] = $row["email"];
        $_SESSION['user_phone'] = $row["phone"];
        $_SESSION['user_image'] = $row["image"];
        $_SESSION['user_kebele_id'] = $row["kebele_id"];
        $_SESSION['role'] = $row["role"];
        $_SESSION['status'] = $row["status"];

        switch ($row["role"]) {
            case "admin":
                header("Location: ../../Admin Dashboard/dashboardHome.php");
                break;
            case "staff":
                header("Location: ../../Staff/staff.php");
                break;
            case "user":
                header("Location: ../../Hermata home/index.php");
                break;
            default:
                $_SESSION['UNSUCCESS'] = "Unknown role!";
                header("Location: ../../login/login.php");
                break;
        }
        exit;

    } else {
        $_SESSION['UNSUCCESS'] = "Invalid password!";
        header("Location: ../../login/login.php");
        exit;
    }
}