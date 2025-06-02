<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Management</title>
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #858796;
      --success-color: #1cc88a;
      --danger-color: #e74a3b;
      --light-color: #f8f9fc;
      --dark-color: #5a5c69;
      --info-color: #36b9cc;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--light-color);
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 1px solid var(--secondary-color);
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: var(--primary-color);
      color: white;
    }

    table tr:nth-child(even) {
      background-color: var(--light-color);
    }

    table tr:hover {
      background-color: var(--info-color);
      color: white;
    }

    .edit-btn {
      background-color: var(--info-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .edit-btn:hover {
      background-color: var(--dark-color);
    }

    .delete-btn {
      background-color: var(--danger-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: var(--dark-color);
    }

    .form-container {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background-color: var(--light-color);
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container.active {
      display: block;
    }

    .form-container h3 {
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .form-container label {
      display: block;
      margin-bottom: 5px;
      color: var(--dark-color);
    }

    .form-container input,
    .form-container select {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid var(--secondary-color);
      border-radius: 5px;
    }

    .form-container button {
      background-color: var(--success-color);
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: var(--dark-color);
    }
    
    /* Validation styles */
    .error-message {
      color: var(--danger-color);
      font-size: 0.8em;
      margin-top: -8px;
      margin-bottom: 10px;
    }
    
    .valid {
      border-color: var(--success-color);
    }
    
    .invalid {
      border-color: var(--danger-color);
    }
    
    .confirmation-dialog {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .confirmation-content {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      max-width: 400px;
      text-align: center;
    }
    
    .confirmation-buttons {
      margin-top: 20px;
    }
    
    .confirmation-buttons button {
      margin: 0 10px;
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    
    .confirm-btn {
      background-color: var(--danger-color);
      color: white;
    }
    
    .cancel-btn {
      background-color: var(--secondary-color);
      color: white;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
</head>

<body>
    <?php include "../commonAdmin.php"; ?>
 
  <div class="container">
    <h1>Residents Management</h1>

    <?php
    // Database connection
    include "../../reportDB/dbconnection.php"; 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hmreportsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle Delete User
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']); // Sanitize input
        $sql = "DELETE FROM residents WHERE residence_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: residentsManagement.php"); // Refresh the page
        exit();
    }

    // Handle Edit User
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
        $errors = [];
        
        // Validate and sanitize inputs
        $edit_id = intval($_POST['id']); // Sanitize input
        $fname = htmlspecialchars(trim($_POST['fname']));
        $mname = htmlspecialchars(trim($_POST['mname']));
        $fathersName = htmlspecialchars(trim($_POST['fathersName']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $email = htmlspecialchars(trim($_POST['email']));
        $role = htmlspecialchars(trim($_POST['role']));
        
        // Validate names (letters only, no numbers or special chars except spaces, hyphens, apostrophes)
        $nameRegex = "/^[A-Za-z\s\-']{2,50}$/";
        
        if (!preg_match($nameRegex, $fname)) {
            $errors['fname'] = "First name must contain only letters (2-50 characters)";
        }
        
        if (!empty($mname) && !preg_match($nameRegex, $mname)) {
            $errors['mname'] = "Middle name must contain only letters (2-50 characters)";
        }
        
        if (!preg_match($nameRegex, $fathersName)) {
            $errors['fathersName'] = "Father's name must contain only letters (2-50 characters)";
        }
        
        // Validate Ethiopian phone number
        if (!preg_match("/^(\+2519\d{8}|09\d{8})$/", $phone)) {
            $errors['phone'] = "Phone must be a valid Ethiopian number (+2519xxxxxxxx or 09xxxxxxxx)";
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address";
        }
        
        // Validate role
        $validRoles = ['resident', 'staff', 'admin'];
        if (!in_array($role, $validRoles)) {
            $errors['role'] = "Invalid role selected";
        }
        
        // If no errors, update the database
        if (empty($errors)) {
            $sql = "UPDATE residents SET fname = ?, mname = ?, fathersName = ?, phone = ?, email = ?, role = ? WHERE residence_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $fname, $mname, $fathersName, $phone, $email, $role, $edit_id);
            $stmt->execute();
            $stmt->close();
            header("Location: residentsManagement.php"); // Refresh the page
            exit();
        } else {
            // Store errors in session to display them after redirect
            $_SESSION['edit_errors'] = $errors;
            $_SESSION['edit_values'] = $_POST;
            header("Location: residentsManagement.php?edit_id=".$edit_id);
            exit();
        }
    }
    ?>

    <!-- Users Table -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT residence_id, fname, mname, fathersName, phone, email, role FROM residents";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['residence_id']}</td>
                        <td>{$row['fname']} {$row['mname']} {$row['fathersName']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                          <button class='edit-btn' onclick=\"editUser({$row['residence_id']}, '{$row['fname']}', '{$row['mname']}', '{$row['fathersName']}', '{$row['phone']}', '{$row['email']}', '{$row['role']}')\">Edit</button>
                          <button class='delete-btn' onclick=\"confirmDelete({$row['residence_id']})\">Delete</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No users found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Edit User Form -->
    <div id="editUserForm" class="form-container <?php echo isset($_GET['edit_id']) ? 'active' : ''; ?>">
      <h3>Edit User</h3>
      <form method="POST" action="" id="editForm" onsubmit="return validateEditForm()">
        <input type="hidden" name="id" id="edit_id" value="<?php echo isset($_SESSION['edit_values']['id']) ? htmlspecialchars($_SESSION['edit_values']['id']) : ''; ?>">
        
        <label for="fname">First Name:</label>
        <input type="text" name="fname" id="edit_fname" 
               value="<?php echo isset($_SESSION['edit_values']['fname']) ? htmlspecialchars($_SESSION['edit_values']['fname']) : ''; ?>"
               class="<?php echo isset($_SESSION['edit_errors']['fname']) ? 'invalid' : ''; ?>">
        <div id="fname-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['fname']) ? htmlspecialchars($_SESSION['edit_errors']['fname']) : ''; ?></div>
        
        <label for="mname">Middle Name:</label>
        <input type="text" name="mname" id="edit_mname" 
               value="<?php echo isset($_SESSION['edit_values']['mname']) ? htmlspecialchars($_SESSION['edit_values']['mname']) : ''; ?>"
               class="<?php echo isset($_SESSION['edit_errors']['mname']) ? 'invalid' : ''; ?>">
        <div id="mname-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['mname']) ? htmlspecialchars($_SESSION['edit_errors']['mname']) : ''; ?></div>
        
        <label for="fathersName">Father's Name:</label>
        <input type="text" name="fathersName" id="edit_fathersName" 
               value="<?php echo isset($_SESSION['edit_values']['fathersName']) ? htmlspecialchars($_SESSION['edit_values']['fathersName']) : ''; ?>"
               class="<?php echo isset($_SESSION['edit_errors']['fathersName']) ? 'invalid' : ''; ?>">
        <div id="fathersName-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['fathersName']) ? htmlspecialchars($_SESSION['edit_errors']['fathersName']) : ''; ?></div>
        
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="edit_phone" 
               value="<?php echo isset($_SESSION['edit_values']['phone']) ? htmlspecialchars($_SESSION['edit_values']['phone']) : ''; ?>"
               class="<?php echo isset($_SESSION['edit_errors']['phone']) ? 'invalid' : ''; ?>">
        <div id="phone-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['phone']) ? htmlspecialchars($_SESSION['edit_errors']['phone']) : ''; ?></div>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="edit_email" 
               value="<?php echo isset($_SESSION['edit_values']['email']) ? htmlspecialchars($_SESSION['edit_values']['email']) : ''; ?>"
               class="<?php echo isset($_SESSION['edit_errors']['email']) ? 'invalid' : ''; ?>">
        <div id="email-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['email']) ? htmlspecialchars($_SESSION['edit_errors']['email']) : ''; ?></div>
        
        <label for="role">Role:</label>
        <select name="role" id="edit_role" class="<?php echo isset($_SESSION['edit_errors']['role']) ? 'invalid' : ''; ?>">
          <option value="resident" <?php echo ((isset($_SESSION['edit_values']['role']) && $_SESSION['edit_values']['role'] === 'resident') ? 'selected' : ''); ?>>Resident</option>
          <option value="staff" <?php echo ((isset($_SESSION['edit_values']['role']) && $_SESSION['edit_values']['role'] === 'staff') ? 'selected' : ''); ?>>Staff</option>
          <option value="admin" <?php echo ((isset($_SESSION['edit_values']['role']) && $_SESSION['edit_values']['role'] === 'admin') ? 'selected' : ''); ?>>Admin</option>
        </select>
        <div id="role-error" class="error-message"><?php echo isset($_SESSION['edit_errors']['role']) ? htmlspecialchars($_SESSION['edit_errors']['role']) : ''; ?></div>
        
        <button type="submit" name="edit_user">Save Changes</button>
      </form>
    </div>
    
    <!-- Delete Confirmation Dialog -->
    <div id="confirmationDialog" class="confirmation-dialog">
      <div class="confirmation-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="confirmation-buttons">
          <button class="cancel-btn" onclick="hideConfirmation()">Cancel</button>
          <button class="confirm-btn" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Clear any existing error messages when the page loads
    window.onload = function() {
      <?php 
      // Clear the error messages after displaying them
      unset($_SESSION['edit_errors']);
      unset($_SESSION['edit_values']);
      ?>
    };
    
    function editUser(id, fname, mname, fathersName, phone, email, role) {
      document.getElementById('editUserForm').classList.add('active');
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_fname').value = fname;
      document.getElementById('edit_mname').value = mname;
      document.getElementById('edit_fathersName').value = fathersName;
      document.getElementById('edit_phone').value = phone;
      document.getElementById('edit_email').value = email;
      document.getElementById('edit_role').value = role;
      
      // Reset validation states
      document.getElementById('edit_fname').classList.remove('invalid', 'valid');
      document.getElementById('edit_mname').classList.remove('invalid', 'valid');
      document.getElementById('edit_fathersName').classList.remove('invalid', 'valid');
      document.getElementById('edit_phone').classList.remove('invalid', 'valid');
      document.getElementById('edit_email').classList.remove('invalid', 'valid');
      document.getElementById('edit_role').classList.remove('invalid', 'valid');
      
      // Clear error messages
      document.getElementById('fname-error').textContent = '';
      document.getElementById('mname-error').textContent = '';
      document.getElementById('fathersName-error').textContent = '';
      document.getElementById('phone-error').textContent = '';
      document.getElementById('email-error').textContent = '';
      document.getElementById('role-error').textContent = '';
    }
    
    // Name validation function
    function validateName(name, fieldName) {
      const nameRegex = /^[A-Za-z\s\-']{2,50}$/;
      if (!nameRegex.test(name)) {
        return `${fieldName} must contain only letters (2-50 characters)`;
      }
      if (/\d/.test(name)) {
        return `${fieldName} cannot contain numbers`;
      }
      if (/[^A-Za-z\s\-']/.test(name)) {
        return `${fieldName} cannot contain special characters`;
      }
      return '';
    }
    
    // Phone validation function
    function validatePhone(phone) {
      const phoneRegex = /^(\+2519\d{8}|09\d{8})$/;
      if (!phoneRegex.test(phone)) {
        return "Phone must be a valid Ethiopian number (+2519xxxxxxxx or 09xxxxxxxx)";
      }
      return '';
    }
    
    // Email validation function
    function validateEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        return "Please enter a valid email address";
      }
      return '';
    }
    
    // Role validation function
    function validateRole(role) {
      const validRoles = ['resident', 'staff', 'admin'];
      if (!validRoles.includes(role)) {
        return "Invalid role selected";
      }
      return '';
    }
    
    // Form validation function
    function validateEditForm() {
      let isValid = true;
      
      // Validate first name
      const fname = document.getElementById('edit_fname').value.trim();
      const fnameError = validateName(fname, 'First name');
      const fnameErrorElement = document.getElementById('fname-error');
      const fnameField = document.getElementById('edit_fname');
      
      if (fnameError) {
        fnameErrorElement.textContent = fnameError;
        fnameField.classList.add('invalid');
        fnameField.classList.remove('valid');
        isValid = false;
      } else {
        fnameErrorElement.textContent = '';
        fnameField.classList.add('valid');
        fnameField.classList.remove('invalid');
      }
      
      // Validate middle name (optional)
      const mname = document.getElementById('edit_mname').value.trim();
      const mnameErrorElement = document.getElementById('mname-error');
      const mnameField = document.getElementById('edit_mname');
      
      if (mname) {
        const mnameError = validateName(mname, 'Middle name');
        if (mnameError) {
          mnameErrorElement.textContent = mnameError;
          mnameField.classList.add('invalid');
          mnameField.classList.remove('valid');
          isValid = false;
        } else {
          mnameErrorElement.textContent = '';
          mnameField.classList.add('valid');
          mnameField.classList.remove('invalid');
        }
      } else {
        mnameErrorElement.textContent = '';
        mnameField.classList.remove('invalid', 'valid');
      }
      
      // Validate father's name
      const fathersName = document.getElementById('edit_fathersName').value.trim();
      const fathersNameError = validateName(fathersName, 'Father\'s name');
      const fathersNameErrorElement = document.getElementById('fathersName-error');
      const fathersNameField = document.getElementById('edit_fathersName');
      
      if (fathersNameError) {
        fathersNameErrorElement.textContent = fathersNameError;
        fathersNameField.classList.add('invalid');
        fathersNameField.classList.remove('valid');
        isValid = false;
      } else {
        fathersNameErrorElement.textContent = '';
        fathersNameField.classList.add('valid');
        fathersNameField.classList.remove('invalid');
      }
      
      // Validate phone
      const phone = document.getElementById('edit_phone').value.trim();
      const phoneError = validatePhone(phone);
      const phoneErrorElement = document.getElementById('phone-error');
      const phoneField = document.getElementById('edit_phone');
      
      if (phoneError) {
        phoneErrorElement.textContent = phoneError;
        phoneField.classList.add('invalid');
        phoneField.classList.remove('valid');
        isValid = false;
      } else {
        phoneErrorElement.textContent = '';
        phoneField.classList.add('valid');
        phoneField.classList.remove('invalid');
      }
      
      // Validate email
      const email = document.getElementById('edit_email').value.trim();
      const emailError = validateEmail(email);
      const emailErrorElement = document.getElementById('email-error');
      const emailField = document.getElementById('edit_email');
      
      if (emailError) {
        emailErrorElement.textContent = emailError;
        emailField.classList.add('invalid');
        emailField.classList.remove('valid');
        isValid = false;
      } else {
        emailErrorElement.textContent = '';
        emailField.classList.add('valid');
        emailField.classList.remove('invalid');
      }
      
      // Validate role
      const role = document.getElementById('edit_role').value;
      const roleError = validateRole(role);
      const roleErrorElement = document.getElementById('role-error');
      const roleField = document.getElementById('edit_role');
      
      if (roleError) {
        roleErrorElement.textContent = roleError;
        roleField.classList.add('invalid');
        roleField.classList.remove('valid');
        isValid = false;
      } else {
        roleErrorElement.textContent = '';
        roleField.classList.add('valid');
        roleField.classList.remove('invalid');
      }
      
      return isValid;
    }
    
    // Delete confirmation functions
    let userIdToDelete = null;
    
    function confirmDelete(userId) {
      userIdToDelete = userId;
      document.getElementById('confirmationDialog').style.display = 'flex';
    }
    
    function hideConfirmation() {
      document.getElementById('confirmationDialog').style.display = 'none';
      userIdToDelete = null;
    }
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
      if (userIdToDelete) {
        window.location.href = '?delete_id=' + userIdToDelete;
      }
    });
    
    // Field validation on blur
    document.getElementById('edit_fname').addEventListener('blur', function() {
      const fname = this.value.trim();
      const error = validateName(fname, 'First name');
      const errorElement = document.getElementById('fname-error');
      
      if (error) {
        errorElement.textContent = error;
        this.classList.add('invalid');
        this.classList.remove('valid');
      } else {
        errorElement.textContent = '';
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
    });
    
    document.getElementById('edit_mname').addEventListener('blur', function() {
      const mname = this.value.trim();
      const errorElement = document.getElementById('mname-error');
      
      if (mname) {
        const error = validateName(mname, 'Middle name');
        if (error) {
          errorElement.textContent = error;
          this.classList.add('invalid');
          this.classList.remove('valid');
        } else {
          errorElement.textContent = '';
          this.classList.add('valid');
          this.classList.remove('invalid');
        }
      } else {
        errorElement.textContent = '';
        this.classList.remove('invalid', 'valid');
      }
    });
    
    document.getElementById('edit_fathersName').addEventListener('blur', function() {
      const fathersName = this.value.trim();
      const error = validateName(fathersName, 'Father\'s name');
      const errorElement = document.getElementById('fathersName-error');
      
      if (error) {
        errorElement.textContent = error;
        this.classList.add('invalid');
        this.classList.remove('valid');
      } else {
        errorElement.textContent = '';
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
    });
    
    document.getElementById('edit_phone').addEventListener('blur', function() {
      const phone = this.value.trim();
      const error = validatePhone(phone);
      const errorElement = document.getElementById('phone-error');
      
      if (error) {
        errorElement.textContent = error;
        this.classList.add('invalid');
        this.classList.remove('valid');
      } else {
        errorElement.textContent = '';
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
    });
    
    document.getElementById('edit_email').addEventListener('blur', function() {
      const email = this.value.trim();
      const error = validateEmail(email);
      const errorElement = document.getElementById('email-error');
      
      if (error) {
        errorElement.textContent = error;
        this.classList.add('invalid');
        this.classList.remove('valid');
      } else {
        errorElement.textContent = '';
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
    });
    
    document.getElementById('edit_role').addEventListener('change', function() {
      const role = this.value;
      const error = validateRole(role);
      const errorElement = document.getElementById('role-error');
      
      if (error) {
        errorElement.textContent = error;
        this.classList.add('invalid');
        this.classList.remove('valid');
      } else {
        errorElement.textContent = '';
        this.classList.add('valid');
        this.classList.remove('invalid');
      }
    });
  </script>
</body>

</html>