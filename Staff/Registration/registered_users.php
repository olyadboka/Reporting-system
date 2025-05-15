<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
$sql = "SELECT residence_id, fname, mname, fathersName, age, birthdate, phone, email, address, fatherFullName, fatherPhone, motherFullName, motherPhone, emergencyName, emergencyPhone, photo FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
  <link rel="stylesheet" href="../Registration/generalStyles/registerd.css">
        <link rel="stylesheet" href="../Registration/generalStyles/all.min.css"></head>
<body>
    <nav>
        <h3>Registered Users</h3>
        <a href="register.php" >Register New User</a>
    </nav>
    <div class="container">
        <div class="header">
            <h2>List of Registered Users</h2>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Residence ID</th>
                            <th>First Name</th>
                            <th>Father's Name</th>
                            <th>Grandfather's Name</th>
                            <th>Age</th>
                            <th>Birthdate</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Father's Full Name</th>
                            <th>Father's Phone</th>
                            <th>Mother's Full Name</th>
                            <th>Mother's Phone</th>
                            <th>Emergency Contact</th>
                            <th>Emergency Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['photo'])): ?>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['photo']); ?>" alt="Profile Picture" class="profile-img">
                                    <?php else: ?>
                                        <img src="default-profile.png" alt="Default Profile" class="profile-img">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_user.php?residence_id=<?php echo $row['residence_id']; ?>">
                                        <?php echo $row['residence_id']; ?>
                                    </a>
                                </td>
                                <td><?php echo $row['fname']; ?></td>
                                <td><?php echo $row['mname']; ?></td>
                                <td><?php echo $row['fathersName']; ?></td>
                                <td><?php echo $row['age']; ?></td>
                                <td><?php echo $row['birthdate']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['fatherFullName']; ?></td>
                                <td><?php echo $row['fatherPhone']; ?></td>
                                <td><?php echo $row['motherFullName']; ?></td>
                                <td><?php echo $row['motherPhone']; ?></td>
                                <td><?php echo $row['emergencyName']; ?></td>
                                <td><?php echo $row['emergencyPhone']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>