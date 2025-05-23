let display = document.getElementById("display");

// Append number to the display
function appendNumber(number) {
  display.value += number;
}

// Append operator to the display
function appendOperator(operator) {
  if (display.value !== "" && !isOperator(display.value.slice(-1))) {
    display.value += ` ${operator} `;
  }
}

// Append decimal point
function appendDot() {
  if (!display.value.includes(".")) {
    display.value += ".";
  }
}

// Clear the display
function clearDisplay() {
  display.value = "";
}

// Check if last character is an operator
function isOperator(char) {
  return ["+", "-", "*", "/"].includes(char);
}

// Calculate the result
function calculateResult() {
  try {
    let expression = display.value.replace(/×/g, "*").replace(/÷/g, "/");
    display.value = eval(expression);
  } catch (error) {
    display.value = "Error";
  }
}
