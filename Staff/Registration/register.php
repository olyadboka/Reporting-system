<?php
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Display registration errors if they exist
if (isset($_SESSION['reg_error'])) {
    echo '<div class="error-message">' . htmlspecialchars($_SESSION['reg_error']) . '</div>';
    unset($_SESSION['reg_error']);
}

// Calculate max date (18 years ago from today) and min date (100 years ago)
$maxDate = date('Y-m-d', strtotime('-18 years'));
$minDate = date('Y-m-d', strtotime('-100 years'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Registration</title>
  <link rel="stylesheet" href="generalStyles/all.min.css" />
  <style>
  .error-message {
    color: red;
    font-size: 0.8em;
    margin-top: 5px;
  }

  .input-box {
    margin-bottom: 15px;
  }

  .valid {
    border-color: #00cc66;
  }

  .invalid {
    border-color: #ff4d4d;
  }
  </style>
  <script src="SCRIPT/javascript.js" defer></script>
  <script>
  function previewImage(event) {
    const image = document.querySelector(".profile img");
    const file = event.target.files[0];
    const errorElement = document.getElementById('photo-error');

    if (file) {
      // Validate file type
      const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
      if (!validTypes.includes(file.type)) {
        errorElement.textContent = "Only JPG, PNG or GIF images are allowed.";
        event.target.value = '';
        return;
      }

      // Validate file size (max 2MB)
      if (file.size > 2 * 1024 * 1024) {
        errorElement.textContent = "Image must be less than 2MB.";
        event.target.value = '';
        return;
      }

      errorElement.textContent = '';
      image.src = URL.createObjectURL(file);
      image.alt = "Selected Image";
    }
  }

  function validateNameField(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(`${fieldId}-error`);
    const value = field.value.trim();

    const nameRegex = /^[A-Za-z\s\-']{2,50}$/;

    if (value === '') {
      errorElement.textContent = `${fieldName} is required.`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else if (!nameRegex.test(value)) {
      errorElement.textContent =
        `${fieldName} must contain only letters and can include spaces, hyphens (-), and apostrophes (').`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else if (/\d/.test(value)) {
      errorElement.textContent = `${fieldName} cannot contain numbers.`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else if (/[^A-Za-z\s\-']/.test(value)) {
      errorElement.textContent = `${fieldName} cannot contain special characters.`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = '';
      field.classList.add('valid');
      field.classList.remove('invalid');
      return true;
    }
  }

  function validateDateOfBirth() {
    const birthdateField = document.getElementById('birthdate');
    const errorElement = document.getElementById('birthdate-error');
    const birthdate = new Date(birthdateField.value);
    const today = new Date();

    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 100);

    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 18);

    if (!birthdateField.value) {
      errorElement.textContent = "Birthdate is required.";
      birthdateField.classList.add('invalid');
      birthdateField.classList.remove('valid');
      return false;
    } else if (birthdate > today) {
      errorElement.textContent = "Birthdate cannot be in the future.";
      birthdateField.classList.add('invalid');
      birthdateField.classList.remove('valid');
      return false;
    } else if (birthdate < minDate) {
      errorElement.textContent = "Birthdate is too far in the past (maximum 100 years).";
      birthdateField.classList.add('invalid');
      birthdateField.classList.remove('valid');
      return false;
    } else if (birthdate > maxDate) {
      errorElement.textContent = "You must be at least 18 years old to register.";
      birthdateField.classList.add('invalid');
      birthdateField.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = "";
      birthdateField.classList.add('valid');
      birthdateField.classList.remove('invalid');
      return true;
    }
  }

  function validateAge() {
    const ageField = document.getElementById('age');
    const errorElement = document.getElementById('age-error');
    const age = parseInt(ageField.value);

    if (!ageField.value) {
      errorElement.textContent = "Age is required.";
      ageField.classList.add('invalid');
      ageField.classList.remove('valid');
      return false;
    } else if (isNaN(age)) {
      errorElement.textContent = "Age must be a valid number.";
      ageField.classList.add('invalid');
      ageField.classList.remove('valid');
      return false;
    } else if (age < 18) {
      errorElement.textContent = "You must be at least 18 years old to register.";
      ageField.classList.add('invalid');
      ageField.classList.remove('valid');
      return false;
    } else if (age > 100) {
      errorElement.textContent = "Maximum age is 100 years.";
      ageField.classList.add('invalid');
      ageField.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = "";
      ageField.classList.add('valid');
      ageField.classList.remove('invalid');
      return true;
    }
  }

  function syncAgeAndBirthdate() {
    const birthdateField = document.getElementById('birthdate');
    const ageField = document.getElementById('age');

    // When birthdate changes, update age
    birthdateField.addEventListener('change', function() {
      if (this.value) {
        const birthDate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
          age--;
        }

        ageField.value = age;
        validateAge();
      }
    });

    // When age changes, update birthdate (approximate)
    ageField.addEventListener('change', function() {
      if (this.value && !isNaN(this.value)) {
        const age = parseInt(this.value);
        const today = new Date();
        const birthYear = today.getFullYear() - age;
        const approximateBirthdate =
          `${birthYear}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        birthdateField.value = approximateBirthdate;
        validateDateOfBirth();
      }
    });
  }

  function validatePhoneNumber(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(`${fieldId}-error`);
    const value = field.value.trim();

    // Ethiopian phone number format: +2519xxxxxxxx or 09xxxxxxxx
    const phoneRegex = /^(\+2519\d{8}|09\d{8})$/;

    if (value === '') {
      errorElement.textContent = `${fieldName} is required.`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else if (!phoneRegex.test(value)) {
      errorElement.textContent =
        `${fieldName} must be a valid Ethiopian phone number starting with +2519 or 09 followed by 8 digits.`;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = '';
      field.classList.add('valid');
      field.classList.remove('invalid');
      return true;
    }
  }

  function validateEmail() {
    const emailField = document.getElementById('email');
    const errorElement = document.getElementById('email-error');
    const value = emailField.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (value === '') {
      errorElement.textContent = "Email is required.";
      emailField.classList.add('invalid');
      emailField.classList.remove('valid');
      return false;
    } else if (!emailRegex.test(value)) {
      errorElement.textContent = "Please enter a valid email address.";
      emailField.classList.add('invalid');
      emailField.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = '';
      emailField.classList.add('valid');
      emailField.classList.remove('invalid');
      return true;
    }
  }

  function validateGender() {
    const genderSelected = document.querySelector('input[name="gender"]:checked');
    const errorElement = document.getElementById('gender-error');

    if (!genderSelected) {
      errorElement.textContent = "Gender selection is required.";
      return false;
    } else {
      errorElement.textContent = '';
      return true;
    }
  }

  function validatePhoto() {
    const photoInput = document.getElementById('photo');
    const errorElement = document.getElementById('photo-error');

    if (photoInput.files.length === 0) {
      errorElement.textContent = "Photo is required.";
      return false;
    }

    const file = photoInput.files[0];
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (!validTypes.includes(file.type)) {
      errorElement.textContent = "Only JPG, PNG or GIF images are allowed.";
      return false;
    } else if (file.size > 2 * 1024 * 1024) {
      errorElement.textContent = "Image must be less than 2MB.";
      return false;
    } else {
      errorElement.textContent = '';
      return true;
    }
  }

  function validateForm(event) {
    event.preventDefault();

    // Validate all fields
    const isFnameValid = validateNameField('fname', 'First Name');
    const isMnameValid = validateNameField('mname', 'Father\'s Name');
    const isFathersNameValid = validateNameField('fathersName', 'Grandfather\'s Name');
    const isBirthdateValid = validateDateOfBirth();
    const isAgeValid = validateAge();
    const isPhoneValid = validatePhoneNumber('phone', 'Phone');
    const isEmailValid = validateEmail();
    const isHouseNumberValid = validateField('house-number',
      val => val !== "" && !isNaN(val) && val > 0, "Valid house number is required.");
    const isGenderValid = validateGender();
    const isFatherFullNameValid = validateNameField('fatherFullName', 'Father\'s Full Name');
    const isFatherPhoneValid = validatePhoneNumber('fatherPhone', 'Father\'s Phone');
    const isMotherFullNameValid = validateNameField('motherFullName', 'Mother\'s Full Name');
    const isMotherPhoneValid = validatePhoneNumber('motherPhone', 'Mother\'s Phone');
    const isEmergencyNameValid = validateNameField('emergencyName', 'Emergency Contact Name');
    const isEmergencyPhoneValid = validatePhoneNumber('emergencyPhone', 'Emergency Contact Phone');
    const isPhotoValid = validatePhoto();

    // If all validations pass, submit the form
    if (isFnameValid && isMnameValid && isFathersNameValid &&
      isBirthdateValid && isAgeValid && isPhoneValid && isEmailValid &&
      isHouseNumberValid && isGenderValid &&
      isFatherFullNameValid && isFatherPhoneValid &&
      isMotherFullNameValid && isMotherPhoneValid &&
      isEmergencyNameValid && isEmergencyPhoneValid && isPhotoValid) {
      document.getElementById('reportForm').submit();
    }
  }

  // Helper function for generic field validation
  function validateField(fieldId, validationFn, errorMessage) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(`${fieldId}-error`);
    const value = field.value.trim();

    if (!validationFn(value)) {
      errorElement.textContent = errorMessage;
      field.classList.add('invalid');
      field.classList.remove('valid');
      return false;
    } else {
      errorElement.textContent = '';
      field.classList.add('valid');
      field.classList.remove('invalid');
      return true;
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Name fields
    ['fname', 'mname', 'fathersName', 'fatherFullName', 'motherFullName', 'emergencyName'].forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('blur', function() {
          const fieldName = fieldId === 'mname' ? 'Father\'s Name' :
            fieldId === 'fathersName' ? 'Grandfather\'s Name' :
            fieldId === 'fatherFullName' ? 'Father\'s Full Name' :
            fieldId === 'motherFullName' ? 'Mother\'s Full Name' :
            fieldId === 'emergencyName' ? 'Emergency Contact Name' : 'First Name';
          validateNameField(fieldId, fieldName);
        });
      }
    });

    // Phone fields
    ['phone', 'fatherPhone', 'motherPhone', 'emergencyPhone'].forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('blur', function() {
          const fieldName = fieldId === 'fatherPhone' ? 'Father\'s Phone' :
            fieldId === 'motherPhone' ? 'Mother\'s Phone' :
            fieldId === 'emergencyPhone' ? 'Emergency Contact Phone' : 'Phone';
          validatePhoneNumber(fieldId, fieldName);
        });
      }
    });

    // Other fields
    document.getElementById('birthdate').addEventListener('change', validateDateOfBirth);
    document.getElementById('age').addEventListener('blur', validateAge);
    document.getElementById('email').addEventListener('blur', validateEmail);

    // Gender radio buttons
    document.querySelectorAll('input[name="gender"]').forEach(radio => {
      radio.addEventListener('change', validateGender);
    });

    // Set min and max dates for birthdate
    document.getElementById('birthdate').min = '<?php echo $minDate; ?>';
    document.getElementById('birthdate').max = '<?php echo $maxDate; ?>';

    // Sync age and birthdate fields
    syncAgeAndBirthdate();
  });
  </script>
  <link rel="stylesheet" href="../Registration/generalStyles/register.css">
  <link rel="stylesheet" href="../Registration/generalStyles/all.min.css">
</head>

<body>

  <nav>
    <a href="../staff.php" class="btn btn-secondary">Back</a>
    <h3>User Registration</h3>
    <a href="registered_users.php"> <i class="fa-solid fa-circle-user"></i>Registered User List</a>
  </nav>
  <div class="header">
    <h3>Hermata Mentina Residents Registration Form</h3>
  </div>

  <form id="reportForm" action="store_data.php" method="POST" enctype="multipart/form-data"
    onsubmit="validateForm(event)">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <div class="container">
      <div class="section display">
        <div class="profile">
          <img src="images/download.png" alt="" />
        </div>
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/gif"
          onchange="previewImage(event)" required />
        <div id="photo-error" class="error-message"></div>
      </div>
      <div class="section">
        <h4>Personal Information</h4>
        <div class="input-box">
          <label for="fname">First Name:</label>
          <input type="text" id="fname" name="fname" required />
          <div id="fname-error" class="error-message"></div>

          <label for="mname">Father's Name:</label>
          <input type="text" id="mname" name="mname" required />
          <div id="mname-error" class="error-message"></div>

          <label for="fathersName">Grandfather's Name:</label>
          <input type="text" id="fathersName" name="fathersName" required />
          <div id="fathersName-error" class="error-message"></div>

          <label for="age">Age:</label>
          <input type="number" id="age" name="age" required min="18" max="100" />
          <div id="age-error" class="error-message"></div>
        </div>
        <div class="input-box">
          <label for="house-number">House-Number:</label>
          <input type="number" id="house-number" name="house-number" required min="1" />
          <div id="house-number-error" class="error-message"></div>

          <div class="gender display">
            <label>Gender:</label>
            <input type="radio" name="gender" id="male" value="male" required> Male
            <input type="radio" name="gender" id="female" value="female" required> Female
            <div id="gender-error" class="error-message"></div>
          </div>

          <label for="birthdate">Birthdate:</label>
          <input type="date" id="birthdate" name="birthdate" required min="<?php echo $minDate; ?>"
            max="<?php echo $maxDate; ?>" />
          <div id="birthdate-error" class="error-message"></div>

          <label for="phone">Phone:</label>
          <input type="tel" id="phone" name="phone" required placeholder="+2519xxxxxxx or 09xxxxxxx" />
          <div id="phone-error" class="error-message"></div>
        </div>
        <div class="input-box">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required />
          <div id="email-error" class="error-message"></div>
        </div>
      </div>
      <div class="section">
        <h4>Parents Information</h4>
        <div class="input-box">
          <label for="fatherFullName">Father's Full Name:</label>
          <input type="text" id="fatherFullName" name="fatherFullName" required />
          <div id="fatherFullName-error" class="error-message"></div>

          <label for="fatherPhone">Father's Phone:</label>
          <input type="tel" id="fatherPhone" name="fatherPhone" required placeholder="+2519xxxxxxx or 09xxxxxxx" />
          <div id="fatherPhone-error" class="error-message"></div>
        </div>
        <div class="input-box">
          <label for="motherFullName">Mother's Full Name:</label>
          <input type="text" id="motherFullName" name="motherFullName" required />
          <div id="motherFullName-error" class="error-message"></div>

          <label for="motherPhone">Mother's Phone:</label>
          <input type="tel" id="motherPhone" name="motherPhone" required placeholder="+2519xxxxxxx or 09xxxxxxx" />
          <div id="motherPhone-error" class="error-message"></div>
        </div>
      </div>
      <div class="section">
        <h4>Emergency Contact</h4>
        <div class="input-box">
          <label for="emergencyName">Emergency Contact Name:</label>
          <input type="text" id="emergencyName" name="emergencyName" required />
          <div id="emergencyName-error" class="error-message"></div>

          <label for="emergencyPhone">Emergency Contact Phone:</label>
          <input type="tel" id="emergencyPhone" name="emergencyPhone" required
            placeholder="+2519xxxxxxx or 09xxxxxxx" />
          <div id="emergencyPhone-error" class="error-message"></div>
        </div>
      </div>
      <div class="submit">
        <input type="submit" value="Submit" />
      </div>
    </div>
  </form>
</body>

</html>