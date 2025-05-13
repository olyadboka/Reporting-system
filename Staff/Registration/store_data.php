<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "hmreportsystem"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate a unique residence_id
do {
    $residence_id = random_int(100000, 999999);
    $check_sql = "SELECT COUNT(*) AS count FROM users WHERE residence_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $residence_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_assoc();
} while ($row['count'] > 0); // Repeat until a unique ID is found

$fname = $_POST['fname'];
$mname = $_POST['mname'];
$fathersName = $_POST['fathersName'];
$age = $_POST['age'];
$birthdate = $_POST['birthdate'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$fatherFullName = $_POST['fatherFullName'];
$fatherPhone = $_POST['fatherPhone'];
$motherFullName = $_POST['motherFullName'];
$motherPhone = $_POST['motherPhone'];
$emergencyName = $_POST['emergencyName'];
$emergencyPhone = $_POST['emergencyPhone'];

$photo = $_FILES['photo']['tmp_name'];
$photo_blob = null;

if (is_uploaded_file($photo)) {
    $photo_blob = file_get_contents($photo); // Read the file as binary data
}

// Insert data into the users table, including the role column
$sql = "INSERT INTO users (residence_id, fname, mname, fathersName, age, birthdate, phone, email, address, fatherFullName, fatherPhone, motherFullName, motherPhone, emergencyName, emergencyPhone, photo, role)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'resident')";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssisssssssssss",
    $residence_id,
    $fname,
    $mname,
    $fathersName,
    $age,
    $birthdate,
    $phone,
    $email,
    $address,
    $fatherFullName,
    $fatherPhone,
    $motherFullName,
    $motherPhone,
    $emergencyName,
    $emergencyPhone,
    $photo_blob
);

if ($stmt->execute()) {
    echo "New record created successfully. Residence ID: " . $residence_id;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>