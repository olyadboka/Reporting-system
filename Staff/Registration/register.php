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
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
      }

      nav {
        background-color:rgb(22, 69, 255);
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      nav h3 {
        margin: 0;
      }

      nav a {
        color: white;
        text-decoration: none;
        font-weight: bold;
      }

      .header {
        text-align: center;
        margin: 20px 0;
      }

      .container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      .section {
        margin-bottom: 20px;
      }

      .input-box {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
      }

      .input-box label {
        flex: 1 1 100%;
        font-weight: bold;
      }

      .input-box input {
        flex: 1 1 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      .profile {
        text-align: center;
        margin-bottom: 20px;
      }

      .profile img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgb(22, 69, 255);
      }

      .submit {
        text-align: center;
      }

      .submit input {
        background-color:rgb(22, 69, 255);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
      }

      .submit input:hover {
        background-color: rgb(22, 69, 255);
      }

      #error-message {
        color: red;
        margin-bottom: 10px;
        text-align: center;
      }

      /* Fix overlapping and ensure proper spacing */
      label {
        margin-bottom: 5px;
        display: block;
      }

      input {
        width: 100%;
        box-sizing: border-box;
      }

      .input-box {
        flex-direction: column;
      }

      .section h4 {
        margin-bottom: 10px;
        color: rgb(22, 69, 255);
      }
    </style>
  </head>
  <body>
    <nav>
      <h3>User Registration</h3>
      <a href="#">Registered User List</a>
    </nav>
    <div class="header">
      <h3>Hermata Mentina Residents Registration Form</h3>
    </div>
    <form action="store_data.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
      <div id="error-message"></div>
      <div class="container">
        <div class="section">
          <div class="profile">
            <img src="" alt="No Image Selected" />
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
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required />

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
