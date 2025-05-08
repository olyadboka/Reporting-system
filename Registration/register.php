<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>user-register</title>
    <link rel="stylesheet" href="general styles/register.css" />
    <link rel="stylesheet" href="general styles/all.min.css" />
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
        const mname = document.getElementById("mname").value.trim();
        const fathersName = document.getElementById("fathersName").value.trim();
        const age = document.getElementById("age").value.trim();
        const birthdate = document.getElementById("birthdate").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const email = document.getElementById("email").value.trim();
        const address = document.getElementById("address").value.trim();
        const fatherFullName = document.getElementById("fatherFullName").value.trim();
        const fatherPhone = document.getElementById("fatherPhone").value.trim();
        const motherFullName = document.getElementById("motherFullName").value.trim();
        const motherPhone = document.getElementById("motherPhone").value.trim();
        const emergencyName = document.getElementById("emergencyName").value.trim();
        const emergencyPhone = document.getElementById("emergencyPhone").value.trim();

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
        if (!phoneRegex.test(fatherPhone)) {
          errorMessage.innerHTML = "Father's phone must start with +251 and 9 digits or 09 and 8 digits.";
          event.preventDefault();
          return false;
        }
        if (!phoneRegex.test(motherPhone)) {
          errorMessage.innerHTML = "Mother's phone must start with +251 and 9 digits or 09 and 8 digits.";
          event.preventDefault();
          return false;
        }
        if (!phoneRegex.test(emergencyPhone)) {
          errorMessage.innerHTML = "Emergency contact phone must start with +251 and 9 digits or 09 and 8 digits.";
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

        if (fatherFullName === "") {
          errorMessage.innerHTML = "Father's Full Name is required.";
          event.preventDefault();
          return false;
        }

        if (motherFullName === "") {
          errorMessage.innerHTML = "Mother's Full Name is required.";
          event.preventDefault();
          return false;
        }

        if (emergencyName === "") {
          errorMessage.innerHTML = "Emergency Contact Name is required.";
          event.preventDefault();
          return false;
        }

        return true;
      }
    </script>
  </head>
  <body>
    <nav>
      <div class="nav1">
        <i class="fa-solid fa-user-plus"></i>
        <h3>user registartion</h3>
      </div>
      <div class="nav2">
        <i class="fa-solid fa-users-line"></i>
        <a href="#">registered user list</a>
      </div>
    </nav>
    <div class="header">
      <h3>hermata mentina residens registartion form</h3>
    </div>
    <form action="store_data.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
      <div id="error-message" style="color: red; margin-bottom: 10px;"></div>
      <div class="container">
        <div class="section hidden">
          <div class="part1">
            <div class="profile">
              <img src="" alt="No Image Selected" />
            </div>
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)" />
          </div>
          <div class="part2">
            <div class="input-box">
              <label for="fname">First Name:</label>
              <input type="text" id="fname" name="fname" required />

              <label for="mname">Father's Name:</label>
              <input type="text" id="mname" name="mname" />

              <label for="fathersName">Grandfather's Name:</label>
              <input type="text" id="fathersName" name="fathersName" required />
            </div>

            <div class="input-box">
              <label for="age">Age: </label>
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
              <input type="text" name="address" id="address" required />
            </div>
          </div>
        </div>
        <div class="section hidden">
          <div class="sec-head">
            <h2>Parents Information</h2>
          </div>
          <div class="part2">
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
        </div>
        <div class="section hidden">
          <div class="sec-head">
            <h2>Emergency Contact</h2>
          </div>
          <div class="part2">
            <div class="input-box">
              <label for="emergencyName">Emergency Contact Name:</label>
              <input type="text" id="emergencyName" name="emergencyName" required />

              <label for="emergencyPhone">Emergency Contact Phone:</label>
              <input type="tel" id="emergencyPhone" name="emergencyPhone" required />
            </div>
          </div>
        </div>
      </div>
      <div class="submit">
        <input type="submit" value="Submit" />
      </div>
    </form>
    <footer></footer>
  </body>
</html>
