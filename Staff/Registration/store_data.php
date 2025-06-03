<?php
session_start();

function redirectWithError($msg) {
    $_SESSION['reg_error'] = $msg;
    header("Location: register.php");
    exit();
}

// Database connection
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
} while ($row['count'] > 0);

// Get form data
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$fathersName = $_POST['fathersName'];
$house_number = $_POST['house-number'];
$gender = $_POST['gender'];
$birthdate = $_POST['birthdate'];
$address = "Hermata Metina";
// $age =$_POST['age'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$fatherFullName = $_POST['fatherFullName'];
$fatherPhone = $_POST['fatherPhone'];
$motherFullName = $_POST['motherFullName'];
$motherPhone = $_POST['motherPhone'];
$emergencyName = $_POST['emergencyName'];
$emergencyPhone = $_POST['emergencyPhone'];

// Handle photo upload
$photo = $_FILES['photo']['tmp_name'];
$photo_blob = null;

if (is_uploaded_file($photo)) {
    $photo_blob = file_get_contents($photo);
}

// Generate random password
$random_password = bin2hex(random_bytes(4));
$hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
$role = 'resident';

// Insert data into database
$sql = "INSERT INTO residents (residence_id, fname, mname, fathersName, house_number, gender, birthdate, phone, email, address, fatherFullName, fatherPhone, motherFullName, motherPhone, emergencyName, emergencyPhone, photo, role, hashed_password)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssissssssssssssss",
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
    $role,
    $hashed_password
);

$holder = "staff";
if ($stmt->execute()) {
    // Log the activity
    $activity = "Registered new resident: $fname $mname ($residence_id)";
    $timestamp = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO activity_logs (activity, user, timestamp) VALUES (?, ?, ?)";
    $log_stmt = $conn->prepare($log_sql);
    $log_stmt->bind_param("sss", $activity, $holder, $timestamp);
    $log_stmt->execute();
    $log_stmt->close();
    
    // Prepare success message with styling
    $success_message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Registration Successful</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .success-container {
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                padding: 30px;
                width: 80%;
                max-width: 500px;
                text-align: center;
                animation: fadeIn 0.5s ease-in-out;
            }
            .success-icon {
                color: #4CAF50;
                font-size: 60px;
                margin-bottom: 20px;
            }
            h2 {
                color: #4CAF50;
                margin-bottom: 20px;
            }
            .details {
                background-color: #f9f9f9;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
                text-align: left;
            }
            .detail-row {
                display: flex;
                margin-bottom: 10px;
            }
            .detail-label {
                font-weight: bold;
                width: 150px;
                color: #555;
            }
            .detail-value {
                flex: 1;
                color: #333;
            }
            .password-note {
                color: #ff5722;
                font-weight: bold;
                margin: 15px 0;
            }
            .action-buttons {
                margin-top: 20px;
            }
            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: all 0.3s;
                margin: 0 10px;
                text-decoration: none;
                display: inline-block;
            }
            .btn-print {
                background-color: #2196F3;
                color: white;
            }
            .btn-print:hover {
                background-color: #0b7dda;
            }
            .btn-home {
                background-color: #4CAF50;
                color: white;
            }
            .btn-home:hover {
                background-color: #45a049;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
    </head>
    <body>
        <div class='success-container'>
            <div class='success-icon'>
                <i class='fas fa-check-circle'></i>
            </div>
            <h2>Registration Successful!</h2>
            
            <div class='details'>
                <div class='detail-row'>
                    <div class='detail-label'>Residence ID:</div>
                    <div class='detail-value'>$residence_id</div>
                </div>
                <div class='detail-row'>
                    <div class='detail-label'>Name:</div>
                    <div class='detail-value'>$fname $mname</div>
                </div>
                <div class='detail-row'>
                    <div class='detail-label'>House Number:</div>
                    <div class='detail-value'>$house_number</div>
                </div>
                <div class='detail-row'>
                    <div class='detail-label'>Phone:</div>
                    <div class='detail-value'>$phone</div>
                </div>
            </div>
            
            <div class='password-note'>
                <i class='fas fa-exclamation-circle'></i> Temporary Password: $random_password
            </div>
            
            <p>Please note down the temporary password. The resident should change it after first login.</p>
            
            <div class='action-buttons'>
                <button class='btn btn-print' onclick='window.print()'>
                    <i class='fas fa-print'></i> Print Details
                </button>
                <a href='register.php' class='btn btn-home'>
                    <i class='fas fa-home'></i> Back to Registration
                </a>
            </div>
        </div>
        
        <script>
            // Automatically print if needed
            // window.onload = function() {
            //     setTimeout(() => {
            //         window.print();
            //     }, 1000);
            // };
        </script>
    </body>
    </html>
    ";
    
    echo $success_message;
} else {
    echo "<div style='color: red; padding: 20px; text-align: center;'>
            <h2>Error Occurred</h2>
            <p>" . $stmt->error . "</p>
            <a href='register.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Go Back</a>
          </div>";
}

$stmt->close();
$conn->close();