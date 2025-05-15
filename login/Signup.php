<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../CSS/Signup.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="signup-box container mt-5">
    <?php
      session_start();
      if (isset($_SESSION["SUCCESS"])) {
        echo "<div class='alert alert-success popup'>" . $_SESSION["SUCCESS"] . "</div>";
        unset($_SESSION["SUCCESS"]);
      } elseif (isset($_SESSION["UNSUCCESS"])) {
        echo "<div class='alert alert-danger popup'>" . $_SESSION["UNSUCCESS"] . "</div>";
        unset($_SESSION["UNSUCCESS"]);
      }
    ?>

    <h1 class="header text-center mb-4">Sign up now</h1>

    <form action="../dataBases.all/loginSignUp/signUpdb.php" method="post" enctype="multipart/form-data"
      class="row g-3">

      <div class="col-md-6">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter your FullName...."
          onkeyup="validateName()">
        <div class="invalid-feedback">Please enter a valid name (letters only).</div>
      </div>

      <div class="col-md-6">
        <label for="house-number" class="form-label">House Number:</label>
        <input type="number" class="form-control" name="house-number" id="house-number"
          placeholder="Enter your House number...." onkeyup="validateHouseNumber()">
        <div class="invalid-feedback">House number must be a positive number.</div>
      </div>

      <div class="col-md-6">
        <label for="id-no" class="form-label">ID Number:</label>
        <input type="number" class="form-control" name="id-no" id="id-no" placeholder="Enter your ID number...."
          onkeyup="validateId()">
        <div class="invalid-feedback">ID must be at least 5 digits.</div>
      </div>

      <div class="col-md-6">
        <label for="phone-number" class="form-label">Phone Number:</label>
        <input type="tel" class="form-control" name="phone-number" id="phone-number"
          placeholder="Enter your phone number...." onkeyup="validatePhone()">
        <div class="invalid-feedback">Please enter a valid phone number.</div>
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email ...."
          onkeyup="validateEmail()">
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>

      <div class="col-md-6">
        <label class="form-label d-block">Gender:</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
          <label class="form-check-label" for="male">Male</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
          <label class="form-check-label" for="female">Female</label>
        </div>
      </div>

      <div class="col-md-6">
        <label for="kebele" class="form-label">Kebele:</label>
        <input type="text" class="form-control" name="kebele" id="kebele" value="HERMATA MENTINA"
          placeholder="HERMATA MENTINA">
      </div>

      <div class="col-md-6">
        <label for="create-password" class="form-label">Password:</label>
        <input type="password" class="form-control" name="create-password" id="create-password"
          placeholder="Enter your New password...." onkeyup="validatePassword()">
        <div class="invalid-feedback">Password must be at least 6 characters.</div>

        <label for="confirm-password" class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" name="confirm-password" id="confirm-password"
          placeholder="Enter your confirm password...." onkeyup="validateConfirmPassword()">
        <div class="invalid-feedback">Passwords do not match.</div>
      </div>

      <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary px-4">
          Sign up
        </button>
      </div>
      <div class="hava-account">
        <p style="color: green;">already I have an accout :<a href="./login.php">Login</a></p>
      </div>

    </form>
  </div>
  <script src="Singup.js"></script>

</body>

</html>