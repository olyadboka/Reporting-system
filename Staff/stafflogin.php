<?php
session_start();

// Redirect to appropriate page if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'staff') {
        header("Location: staff.php");
    } else {
        header("Location: ../report.php");
    }
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $residence_id = trim($_POST['residence_id']);
    $password = trim($_POST['password']);

    // Query to fetch user details from the users table
    $sql = "SELECT id AS user_id, fname AS full_name, role, hashed_password FROM users WHERE residence_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $residence_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['hashed_password'])) {
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['role'] = $user['role'];       

           
            if ($user['role'] === 'staff') {
                header("Location: staff.php");
            } else {
                header("Location: report.php");
            }
            exit();
        } else {
            $error = "Invalid residence ID or password.";
        }
    } else {
        $error = "Invalid residence ID or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Login</title>
  <link rel="stylesheet" href="staffCSS/satfflogin.css">

</head>

<body>
  <nav>
    <h3>Staff Login</h3>
  </nav>
  <div class="container">
    <div class="login-container">
      <div class="head">
        <h2>Staff Login</h2>
      </div>
      <div class="form-container">
        <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="stafflogin.php" method="post">
          <div>
            <label for="residence_id">Residence ID</label>
            <input type="text" id="residence_id" name="residence_id" placeholder="Enter your residence ID" required>
          </div>
          <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <div>
            <input type="submit" value="Login">
          </div>
        </form>
      </div>
    </div>
    <div class="back-img hidden"></div>
  </div>
</body>

</html>