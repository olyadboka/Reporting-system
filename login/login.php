<?php
session_start();
// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../CSS/login.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body  >

  <div class="container mt-5">
    <?php
    if(isset($_SESSION["SUCCESS"])){
      echo "<div class='alert alert-success popup'>".htmlspecialchars($_SESSION["SUCCESS"])."</div>";
      unset($_SESSION["SUCCESS"]);
    } else if (isset($_SESSION["UNSUCCESS"])){
      echo "<div class='alert alert-danger popup'>".htmlspecialchars($_SESSION["UNSUCCESS"])."</div>";
      unset($_SESSION["UNSUCCESS"]);
    }
  ?>
    <div class="login-box mx-auto w-100" style="max-width: 600px;">
      <h1 class="header text-center mb-4">Login now</h1>

      <form action="../dataBasesls/loginSignUp/logindb.php" method="post" class="row g-3">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="col-12">
          <label for="user_id" class="form-label">ID:</label>
          <input type="text" class="form-control" name="user_id" id="user_id" placeholder="Enter your ID..." required
            onkeyup="validateLoginID()" />
          <div class="invalid-feedback">ID must be at least 4 characters.</div>
        </div>

        <div class="col-12">
          <label for="password" class="form-label">Password:</label>
          <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" style="width: 240px; flex: none;"
              placeholder="Enter your password..." required onkeyup="validateLoginPassword()" />
            <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="color: white; font-weight:bold">Show</button>
          </div>
          <div class="invalid-feedback">Password must be at least 6 characters.</div>
        </div>

        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primary px-4">Login</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  // Password visibility toggle
  document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Show' : 'Hide';
  });
  </script>
</body>

</html>