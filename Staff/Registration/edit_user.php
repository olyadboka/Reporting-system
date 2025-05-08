<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the residence_id from the URL
$residence_id = $_GET['residence_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE residence_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $residence_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Handle photo upload
    $photo = $_FILES['photo']['tmp_name'];
    $photo_blob = $user['photo']; // Keep existing photo if no new one is uploaded
    if (is_uploaded_file($photo)) {
        $photo_blob = file_get_contents($photo);
    }

    // Update user details
    $update_sql = "UPDATE users SET fname = ?, mname = ?, fathersName = ?, age = ?, birthdate = ?, phone = ?, email = ?, address = ?, fatherFullName = ?, fatherPhone = ?, motherFullName = ?, motherPhone = ?, emergencyName = ?, emergencyPhone = ?, photo = ? WHERE residence_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "ssssisssssssssss",
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
        $photo_blob,
        $residence_id
    );

    if ($update_stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='registered_users.php';</script>";
    } else {
        echo "Error updating user: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .form-actions {
            text-align: center;
        }

        .form-actions button {
            background-color: rgb(22, 69, 255);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-actions button:hover {
            background-color: rgb(18, 55, 204);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User Information</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo $user['fname']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mname">Father's Name:</label>
                <input type="text" id="mname" name="mname" value="<?php echo $user['mname']; ?>">
            </div>
            <div class="form-group">
                <label for="fathersName">Grandfather's Name:</label>
                <input type="text" id="fathersName" name="fathersName" value="<?php echo $user['fathersName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo $user['birthdate']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fatherFullName">Father's Full Name:</label>
                <input type="text" id="fatherFullName" name="fatherFullName" value="<?php echo $user['fatherFullName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="fatherPhone">Father's Phone:</label>
                <input type="tel" id="fatherPhone" name="fatherPhone" value="<?php echo $user['fatherPhone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="motherFullName">Mother's Full Name:</label>
                <input type="text" id="motherFullName" name="motherFullName" value="<?php echo $user['motherFullName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="motherPhone">Mother's Phone:</label>
                <input type="tel" id="motherPhone" name="motherPhone" value="<?php echo $user['motherPhone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="emergencyName">Emergency Contact Name:</label>
                <input type="text" id="emergencyName" name="emergencyName" value="<?php echo $user['emergencyName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="emergencyPhone">Emergency Contact Phone:</label>
                <input type="tel" id="emergencyPhone" name="emergencyPhone" value="<?php echo $user['emergencyPhone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="photo">Profile Picture:</label>
                <?php if (!empty($user['photo'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>" alt="Profile Picture">
                <?php endif; ?>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <div class="form-actions">
                <button type="submit">Update User</button>
            </div>
        </form>
    </div>
</body>
</html>