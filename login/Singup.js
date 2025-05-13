document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      event.preventDefault();
      let usernameEmail = document.getElementById("usernameEmail").value.trim();
      let password = document.getElementById("password").value.trim();
      let errors = [];

      function displayError(fieldId, message) {
        const errorElement =
          document.getElementById(fieldId + "-error") ||
          document.querySelector("." + fieldId + "-error");
        if (errorElement) {
          errorElement.textContent = message;
        } else {
          console.error(`Error element for ${fieldId} not found.`);
        }
      }

      function clearError(fieldId) {
        const errorElement =
          document.getElementById(fieldId + "-error") ||
          document.querySelector("." + fieldId + "-error");
        if (errorElement) {
          errorElement.textContent = "";
        }
      }

      // Clear previous errors
      clearError("usernameEmail");
      clearError("password");

      // Validate username or email
      if (usernameEmail === "") {
        errors.push("Please enter your username or email.");
        displayError("usernameEmail", "Please enter your username or email.");
        event.preventDefault();
      } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (
          !emailRegex.test(usernameEmail) &&
          usernameEmail.indexOf(" ") !== -1
        ) {
          errors.push("Invalid email format or username contains spaces.");
          displayError(
            "usernameEmail",
            "Invalid email format or username contains spaces."
          );
          event.preventDefault();
        }
      }

      // Validate password
      if (password === "") {
        errors.push("Please enter your password.");
        displayError("password", "Please enter your password.");
        event.preventDefault();
      }

      // Log validation errors if any
      if (errors.length > 0) {
        console.log("Validation Errors:", errors);
      }
    });
  } else {
    console.error("Login form element with ID 'loginForm' not found.");
  }
});
