<?php
session_start();

function redirectWithError($msg) {
    $_SESSION['reg_error'] = $msg;
    header("Location: register.php");
    exit();
}

// Validate required fields
if (empty($_POST['fname'])) {
    redirectWithError("First Name is required.");
}
if (empty($_POST['fathersName'])) {
    redirectWithError("Grandfather's Name is required.");
}
if (empty($_POST['birthdate'])) {
    redirectWithError("Birthdate is required.");
}
if (empty($_POST['phone'])) {
    redirectWithError("Phone is required.");
}
if (empty($_POST['email'])) {
    redirectWithError("Email is required.");
}
if (empty($_POST['address'])) {
    redirectWithError("Address is required.");
}

// Validate phone number
$phone = trim($_POST['phone']);
if (!preg_match('/^(\+2519\d{8}|09\d{8})$/', $phone)) {
    redirectWithError("Phone must start with +251 and 9 digits or 09 and 8 digits.");
}

// Validate email
$email = trim($_POST['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError("Please enter a valid email address.");
}

// Optionally validate age if you have it as a field
if (isset($_POST['age'])) {
    $age = trim($_POST['age']);
    if ($age === "" || !is_numeric($age) || $age <= 0) {
        redirectWithError("Please enter a valid age.");
    }
}

// You can add more validation as needed for other fields...

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
    $check_sql = "SELECT COUNT(*) AS count FROM residents WHERE residence_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $residence_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_assoc();
} while ($row['count'] > 0); // Repeat until a unique ID is found

$fname = $_POST['fname'];
$mname = $_POST['mname'];
$fathersName = $_POST['fathersName'];
$house_number = $_POST['house-number'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
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

// Generate a random password
$random_password = bin2hex(random_bytes(4)); // Generates an 8-character password
$hashed_password = password_hash($random_password, PASSWORD_DEFAULT); // Hash the password

// Define the role explicitly
$role = 'resident';

// Insert data into the users table, excluding the auto_increment `id` column
$sql = "INSERT INTO residents (residence_id, fname, mname, fathersName, house_number,gender, birthdate, phone, email, address, fatherFullName, fatherPhone, motherFullName, motherPhone, emergencyName, emergencyPhone, photo, role, hashed_password)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssissssssssssssss", // Updated to 18 characters
    $residence_id,
    $fname,
    $mname,
    $fathersName,
    $house_number,
    $gender,
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
    $photo_blob,
    $role, // Pass the role variable
    $hashed_password // Pass the hashed password
);

if ($stmt->execute()) {
    echo "New record created successfully.<br>";
    echo "Residence ID: " . $residence_id . "<br>";
    echo "Generated Password: " . $random_password . "<br>"; // Display the generated password
    $activity = "Registered new resident: $fname $mname ($residence_id)";
    $user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'staff'; // or use staff name if available
    $timestamp = date('Y-m-d H:i:s');

    $log_sql = "INSERT INTO activity_log (activity, user, timestamp) VALUES (?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("sss", $activity, $user, $timestamp);
    $log_stmt->execute();
    $log_stmt->close();
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>