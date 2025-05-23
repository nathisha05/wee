// Real-time Name Validation
function validateName() {
  let name = document.getElementById("name").value;
  let errorMessage = document.getElementById("error-message");

  // Regular expression for allowing only letters and spaces
  let namePattern = /^[A-Za-z\s]*$/;

  // If the name contains numbers or special characters
  if (!namePattern.test(name)) {
    errorMessage.textContent =
      "Name should only contain letters and spaces, no numbers or special characters.";
  } else {
    errorMessage.textContent = ""; // Clear the error message
  }
}

function validateForm() {
  let name = document.getElementById("name").value;
  let email = document.getElementById("email").value;
  let course = document.getElementById("course").value;
  let age = document.getElementById("age").value;
  let mobile = document.getElementById("mobile").value;

  // Name Validation (Ensure name does not contain numbers)
  let namePattern = /^[A-Za-z\s]+$/; // Name should only contain letters and spaces
  if (name === "") {
    alert("Name is required.");
    return false;
  } else if (!namePattern.test(name)) {
    alert("Name should not contain numbers or special characters.");
    return false;
  }

  // Email Validation
  let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  if (email === "") {
    alert("Email is required.");
    return false;
  } else if (!emailPattern.test(email)) {
    alert("Invalid email format.");
    return false;
  }

  // Course Validation
  if (course === "") {
    alert("Please select a course.");
    return false;
  }

  // Age Validation
  if (age === "") {
    alert("Age is required.");
    return false;
  } else if (age < 18 || age > 60) {
    alert("Please enter a valid age between 18 and 60.");
    return false;
  }

  // Mobile Validation
  let mobilePattern = /^[6-9]\d{9}$/; // Mobile must start with 6, 7, 8, or 9 and have exactly 10 digits
  if (mobile === "") {
    alert("Mobile number is required.");
    return false;
  } else if (!mobilePattern.test(mobile)) {
    alert(
      "Mobile number must start with 6, 7, 8, or 9 and be exactly 10 digits."
    );
    return false;
  }

  // If all validations pass, show success message
  alert("Registration Successful!");
  return true;
}
