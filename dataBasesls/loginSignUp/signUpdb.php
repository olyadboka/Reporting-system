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
    $role = "user";
    $created_at = date('Y-m-d H:i:s');
    $status = "active";
    $photo = null;

    // Validate required fields
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

    if (!preg_match("/^[0-9]{10}$/", $phoneNumber)) {
        echo "Phone number must be 10 digits!";
        exit;
    }

    // Optional email validation
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     echo "Email is not valid!";
    //     exit;
    // }

    // Check resident
    $sqll = "SELECT residence_id, photo FROM residents WHERE residence_id = ?";
    $stmt = mysqli_prepare($con, $sqll);
    mysqli_stmt_bind_param($stmt, "s", $idNumber);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) == 0) {
        echo "Resident with this ID number does not exist!";
        exit;
    }

    $row = mysqli_fetch_assoc($res);
    $residence_id = $row['residence_id'];
    $photo = isset($row['photo']) ? $row['photo'] : null;

    // Use prepared statement for insert
    $sql2 = "INSERT INTO users (
                full_name, email, password, phone, role, created_at, kebele_id, gender, house_no, photo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt2 = mysqli_prepare($con, $sql2);
    mysqli_stmt_bind_param($stmt2, "ssssssssss", 
        $name, $email, $password, $phoneNumber, $role, $created_at, 
        $residence_id, $gender, $houseNumber, $photo
    );

    if (mysqli_stmt_execute($stmt2)) {
        $_SESSION['SUCCESS'] = "User created successfully";
        header("Location: ../login/login.php");
        exit;
    } else {
        $_SESSION['UNSUCCESS'] = "User not created";
        echo "Error: " . mysqli_error($con);
        header("Location: ../login/Signup.php");
        exit;
    }
}