<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Registration</title>
    
    <link rel="stylesheet" href="generalStyles/all.min.css" />
    <script src="SCRIPT/javascript.js" defer></script>
    <script>
      function previewImage(event) {
        const image = document.querySelector(".profile img");
        const file = event.target.files[0];
        if (file) {
          image.src = URL.createObjectURL(file);
          image.alt = "Selected Image";
        }
      }

      function validateForm(event) {
        const errorMessage = document.getElementById("error-message");
        errorMessage.innerHTML = "";

        const fname = document.getElementById("fname").value.trim();
        const fathersName = document.getElementById("fathersName").value.trim();
        const age = document.getElementById("age").value.trim();
        const birthdate = document.getElementById("birthdate").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const email = document.getElementById("email").value.trim();
        const address = document.getElementById("address").value.trim();

        if (fname === "") {
          errorMessage.innerHTML = "First Name is required.";
          event.preventDefault();
          return false;
        }

        if (fathersName === "") {
          errorMessage.innerHTML = "Grandfather's Name is required.";
          event.preventDefault();
          return false;
        }

        if (age === "" || isNaN(age) || age <= 0) {
          errorMessage.innerHTML = "Please enter a valid age.";
          event.preventDefault();
          return false;
        }

        if (birthdate === "") {
          errorMessage.innerHTML = "Birthdate is required.";
          event.preventDefault();
          return false;
        }

        const phoneRegex = /^(\+2519\d{8}|09\d{8})$/;
        if (!phoneRegex.test(phone)) {
          errorMessage.innerHTML = "Phone must start with +251 and 9 digits or 09 and 8 digits.";
          event.preventDefault();
          return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          errorMessage.innerHTML = "Please enter a valid email address.";
          event.preventDefault();
          return false;
        }

        if (address === "") {
          errorMessage.innerHTML = "Address is required.";
          event.preventDefault();
          return false;
        }

        return true;
      }
    </script>
  <link rel="stylesheet" href="../Registration/generalStyles/register.css">
        <link rel="stylesheet" href="../Registration/generalStyles/all.min.css">
  </head>
  <body>
    <nav>
      <h3>User Registration</h3>
      <a href="registered_users.php"> <i class="fa-solid fa-circle-user"></i>Registered User List</a>
    </nav>
    <div class="header">
      <h3>Hermata Mentina Residents Registration Form</h3>
    </div>
    <?php
      session_start();

      $error = '';
      $success = '';

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // Collect and trim all fields
          $fname         = trim($_POST['fname'] ?? '');
          $mname         = trim($_POST['mname'] ?? '');
          $fathersName   = trim($_POST['fathersName'] ?? '');
          $house_number  = trim($_POST['house-number'] ?? '');
          $gender        = trim($_POST['gender'] ?? '');
          $birthdate     = trim($_POST['birthdate'] ?? '');
          $address       = trim($_POST['address'] ?? '');
          $phone         = trim($_POST['phone'] ?? '');
          $email         = trim($_POST['email'] ?? '');
          $fatherFullName = trim($_POST['fatherFullName'] ?? '');
          $fatherPhone    = trim($_POST['fatherPhone'] ?? '');
          $motherFullName = trim($_POST['motherFullName'] ?? '');
          $motherPhone    = trim($_POST['motherPhone'] ?? '');
          $emergencyName  = trim($_POST['emergencyName'] ?? '');
          $emergencyPhone = trim($_POST['emergencyPhone'] ?? '');

          // Required fields
          if ($fname === '')            $error = "First Name is required.";
          elseif ($fathersName === '')  $error = "Grandfather's Name is required.";
          elseif ($birthdate === '')    $error = "Birthdate is required.";
          elseif ($phone === '')        $error = "Phone is required.";
          elseif ($email === '')        $error = "Email is required.";
          elseif ($address === '')      $error = "Address is required.";

          // Phone validation
          elseif (!preg_match('/^(\+2519\d{8}|09\d{8})$/', $phone)) {
              $error = "Phone must start with +251 and 9 digits or 09 and 8 digits.";
          }

          // Email validation
          elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $error = "Please enter a valid email address.";
          }

          // Age validation (must be 18+)
          else {
              try {
                  $birthDateObj = new DateTime($birthdate);
                  $today = new DateTime();
                  $age = $today->diff($birthDateObj)->y;
                  if ($age < 18) {
                      $error = "You're under age. Age must be 18 or older.";
                  }
              } catch (Exception $e) {
                  $error = "Invalid birthdate format.";
              }
          }

          // If no error, you can proceed to store_data.php or insert into DB here
          if (!$error) {
              // Example: include 'store_data.php';
              // Or put your DB insert code here
              // $success = "Registration successful!";
          }
      }

      if (isset($_SESSION['reg_error'])) {
        echo '<div id="error-message" style="color:red;">' . htmlspecialchars($_SESSION['reg_error']) . '</div>';
        unset($_SESSION['reg_error']);
      }
    ?>
    <form action="store_data.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
      <div id="error-message"></div>
      <div class="container">
        <div class="section display">
          <div class="profile">
            <img src="images/download.png" alt="" />
          </div>
          <label for="photo">Photo:</label>
          <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)" />
        </div>
        <div class="section">
          <h4>Personal Information</h4>
          <div class="input-box">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required />

            <label for="mname">Father's Name:</label>
            <input type="text" id="mname" name="mname" />

            <label for="fathersName">Grandfather's Name:</label>
            <input type="text" id="fathersName" name="fathersName" required />
          </div>
          <div class="input-box">
            <label for="house-number:">House-Number:</label>
            <input type="number" id="house-number" name="house-number" required />
           <div class="gender display">
             <label for="gender">Gender:</label>
          <input type="radio" name="gender" id="male"  require>male
                    <input type="radio" name="gender" id="female"  require>female
           </div>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required />

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required />
          </div>
          <div class="input-box">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required />
          </div>
        </div>
        <div class="section">
          <h4>Parents Information</h4>
          <div class="input-box">
            <label for="fatherFullName">Father's Full Name:</label>
            <input type="text" id="fatherFullName" name="fatherFullName" required />

            <label for="fatherPhone">Father's Phone:</label>
            <input type="tel" id="fatherPhone" name="fatherPhone" required />
          </div>
          <div class="input-box">
            <label for="motherFullName">Mother's Full Name:</label>
            <input type="text" id="motherFullName" name="motherFullName" required />

            <label for="motherPhone">Mother's Phone:</label>
            <input type="tel" id="motherPhone" name="motherPhone" required />
          </div>
        </div>
        <div class="section">
          <h4>Emergency Contact</h4>
          <div class="input-box">
            <label for="emergencyName">Emergency Contact Name:</label>
            <input type="text" id="emergencyName" name="emergencyName" required />

            <label for="emergencyPhone">Emergency Contact Phone:</label>
            <input type="tel" id="emergencyPhone" name="emergencyPhone" required />
          </div>
        </div>
        <div class="submit">
          <input type="submit" value="Submit" />
        </div>
      </div>
    </form>
  </body>
</html>
