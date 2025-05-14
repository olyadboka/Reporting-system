<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../CSS/login.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
  <!-- Wrapper with max-width and centering -->
  <div class="container mt-5">
    <div class="login-box mx-auto w-100" style="max-width: 600px;">
      <h1 class="header text-center mb-4">Login now</h1>

      <form action="./login/loginDB.php" method="post" enctype="multipart/form-data" class="row g-3">

        <div class="col-12">
          <label for="name" class="form-label">ID:</label>
          <input type="text" class="form-control" name="name" id="name" placeholder="Enter your ID..."
            onkeyup="validateLoginID()" />
          <div class="invalid-feedback">ID must be at least 4 characters.</div>
        </div>

        <div class="col-12">
          <label for="create-password" class="form-label">Password:</label>
          <input type="password" class="form-control" name="create-password" id="create-password"
            placeholder="Enter your password..." onkeyup="validateLoginPassword()" />
          <div class="invalid-feedback">Password must be at least 6 characters.</div>
        </div>

        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primary px-4">Login</button>
        </div>
      </form>
    </div>
  </div>

  <script src="login.js"></script>
</body>

</html>