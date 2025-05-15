const idInput = document.getElementById("name");
const passwordInput = document.getElementById("create-password");

function validateLoginID() {
  const isValid = idInput.value.trim().length >= 4;
  validateInput(idInput, isValid);
}

function validateLoginPassword() {
  const isValid = passwordInput.value.length >= 6;
  validateInput(passwordInput, isValid);
}

function validateInput(inputElement, isValid) {
  if (isValid) {
    inputElement.classList.add("is-valid");
    inputElement.classList.remove("is-invalid");
  } else {
    inputElement.classList.add("is-invalid");
    inputElement.classList.remove("is-valid");
  }
}
