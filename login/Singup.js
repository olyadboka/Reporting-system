const nameInput = document.getElementById("name");
const houseNumberInput = document.getElementById("house-number");
const idInput = document.getElementById("id-no");
const phoneInput = document.getElementById("phone-number");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("create-password");
const confirmPasswordInput = document.getElementById("confirm-password");

function validateName() {
  const regex = /^[a-zA-Z\s]+$/;
  validateInput(nameInput, regex.test(nameInput.value.trim()));
}

function validateHouseNumber() {
  validateInput(houseNumberInput, houseNumberInput.value > 0);
}

function validateId() {
  validateInput(idInput, idInput.value.length >= 5);
}

function validatePhone() {
  const regex = /^\+?\d{10,15}$/;
  validateInput(phoneInput, regex.test(phoneInput.value.trim()));
}

function validateEmail() {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  validateInput(emailInput, regex.test(emailInput.value.trim()));
}

function validatePassword() {
  const valid = passwordInput.value.length >= 6;
  validateInput(passwordInput, valid);
  if (confirmPasswordInput.value) validateConfirmPassword();
}

function validateConfirmPassword() {
  const match =
    confirmPasswordInput.value === passwordInput.value &&
    passwordInput.value.length >= 6;
  validateInput(confirmPasswordInput, match);
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
