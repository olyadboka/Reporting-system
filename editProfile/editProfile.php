<?php 
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location: ../login/login.php");
  exit();
}
include "../dataBasesls/dbConnection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .password-fields {
      display: none;
      border: 1px solid #007bff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 40px;
      border-radius: 10px;
      margin-top: 20px;
      background-color: #ffffff;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
    .form-control {
      max-width: 350px;
      margin: auto;
      transition: background-color 0.3s;
      font-size: 16px;
    }
    .feedback-list {
      list-style-type: none;
      padding: 0;
      margin: 10px 0;
      font-size: 0.9rem;
    }
    .feedback-list li {
      padding: 5px 0;
      position: relative;
    }
    .invalid-feedback { color: red; }
    .valid-feedback { color: green; }
    .error-icon {
      position: absolute;
      left: -20px;
      top: 0;
      color: red;
      display: none;
    }
    .error-icon.visible { display: inline; }
    .password-message { font-size: 0.9rem; color: #6c757d; }
    .alert-dismissible {
      max-width: 600px;
      margin: 20px auto;
    }
    #password-errors {
      color: red;
      margin-top: 10px;
      font-size: 0.9rem;
    }
    #password-match-error {
      color: red;
      margin-top: 5px;
      font-size: 0.9rem;
      display: none;
    }
  </style>

  <script>
    function validateForm() {
      const role = "<?php echo $_SESSION['role']; ?>";
      if (role !== 'resident') {
        alert("Only residents can change their password.");
        return false;
      }

      const newPassword = document.getElementById("new_password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      if (newPassword !== confirmPassword) {
        document.getElementById("password-match-error").style.display = "block";
        return false;
      }

      return true;
    }

    function showPasswordFields() {
      const passwordFields = document.querySelector('.password-fields');
      passwordFields.style.display = 'block';
      passwordFields.classList.add('border', 'shadow');
      
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.display = 'none';
      });
    }

    function validatePassword() {
      const newPassword = document.getElementById("new_password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      const requirementsFeedback = document.getElementById("password-requirements-feedback");
      const errorContainer = document.getElementById("password-errors");
      const matchError = document.getElementById("password-match-error");

      const criteria = {
        length: newPassword.length >= 8,
        uppercase: /[A-Z]/.test(newPassword),
        lowercase: /[a-z]/.test(newPassword),
        number: /\d/.test(newPassword),
        special: /[@$!%*?&]/.test(newPassword)
      };

      // Update visual feedback
      updateRequirementFeedback("length", criteria.length);
      updateRequirementFeedback("uppercase", criteria.uppercase);
      updateRequirementFeedback("lowercase", criteria.lowercase);
      updateRequirementFeedback("number", criteria.number);
      updateRequirementFeedback("special", criteria.special);

      // Build compact error message
      let errorMessages = [];
      if (!criteria.length) errorMessages.push("8+ characters");
      if (!criteria.uppercase) errorMessages.push("uppercase letter");
      if (!criteria.lowercase) errorMessages.push("lowercase letter");
      if (!criteria.number) errorMessages.push("number");
      if (!criteria.special) errorMessages.push("special character (@$!%*?&)");

      if (errorMessages.length > 0) {
        errorContainer.textContent = "Missing: " + errorMessages.join(", ");
        errorContainer.style.display = "block";
      } else {
        errorContainer.style.display = "none";
      }

      const allValid = criteria.length && criteria.uppercase && criteria.lowercase && criteria.number && criteria.special;

      const newPasswordField = document.getElementById("new_password");
      if (allValid) {
        newPasswordField.style.backgroundColor = "#d4edda";
        requirementsFeedback.textContent = "Password meets all requirements.";
        requirementsFeedback.className = "text-success mt-2";
      } else {
        newPasswordField.style.backgroundColor = "#f8d7da";
        requirementsFeedback.textContent = "";
        requirementsFeedback.className = "mt-2";
      }

      // Password match feedback
      if (confirmPassword.length > 0) {
        if (newPassword === confirmPassword) {
          matchError.style.display = "none";
        } else {
          matchError.style.display = "block";
        }
      } else {
        matchError.style.display = "none";
      }
    }
    
    function updateRequirementFeedback(type, isValid) {
      const element = document.getElementById(`${type}_new`);
      const icon = document.getElementById(`${type}_icon`);
      
      if (isValid) {
        element.className = "valid-feedback";
        icon.classList.remove("visible");
      } else {
        element.className = "invalid-feedback";
        icon.classList.add("visible");
      }
    }
    
    function closeAlert(button) {
      const alertDiv = button.parentElement;
      alertDiv.style.display = 'none';
    }
  </script>
</head>

<body>
<?php include "../common/header.php"; ?>

<div class="container mt-5">
  <div class="card">
    <div class="card-body text-center">
      <div class="card__avatar mb-3">
        <?php  
        echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Profile" style="width:100px;height:100px;border-radius:50%; object-fit:cover;">';
        ?>
      </div>
      <h5 class="card-title"><?php echo "{$_SESSION['user_name']}";?></h5>
      <h6 class="card-subtitle mb-2 text-muted"><?php echo "{$_SESSION['role']}";?></h6>
      <p class="card-text">Email: <span><?php echo "{$_SESSION['user_email']}";?></span></p>

      <button class="btn btn-primary" onclick="showPasswordFields()">Edit Your Profile</button>

      <div class="password-fields mt-4">
        <h3>Change Password</h3>
        <form action="" method="POST" onsubmit="return validateForm();">
          <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required oninput="validatePassword()">
            <ul class="feedback-list">
              <li id="length_new" class="invalid-feedback">At least <strong>8 characters</strong> long <span id="length_icon" class="error-icon">❌</span></li>
              <li id="uppercase_new" class="invalid-feedback">At least one <strong>uppercase letter</strong> <span id="uppercase_icon" class="error-icon">❌</span></li>
              <li id="lowercase_new" class="invalid-feedback">At least one <strong>lowercase letter</strong> <span id="lowercase_icon" class="error-icon">❌</span></li>
              <li id="number_new" class="invalid-feedback">At least one <strong>number</strong> <span id="number_icon" class="error-icon">❌</span></li>
              <li id="special_new" class="invalid-feedback">At least one <strong>special character</strong> (e.g., @$!%*?&) <span id="special_icon" class="error-icon">❌</span></li>
            </ul>
            <div id="password-requirements-feedback" class="mt-2"></div>
            <div id="password-errors"></div>
          </div>

          <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required oninput="validatePassword()">
            <div id="password-match-error">Passwords do not match</div>
          </div>

          <button type="submit" class="btn btn-success">Change Password</button>
        </form>
      </div>

      <?php
      if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['new_password'])) {
          if ($_SESSION['role'] !== 'resident') {
              echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Only residents can change their password.
                      <button type="button" class="close" onclick="closeAlert(this)" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          } else {
              $newPassword = htmlspecialchars(trim($_POST['new_password']));
              $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

              $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_id=?");
              $stmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);

              if ($stmt->execute()) {
                  echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                          Password changed successfully!
                          <button type="button" class="close" onclick="closeAlert(this)" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';
              } else {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          Error changing password.
                          <button type="button" class="close" onclick="closeAlert(this)" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';
              }

              $stmt->close();
              $conn->close();
          }
      }
      ?>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>