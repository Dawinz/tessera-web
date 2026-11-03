(function () {
  const clock = document.getElementById("clock");
  const openCalculator = document.getElementById("openCalculator");
  const closeCalculator = document.getElementById("closeCalculator");
  const calculatorOverlay = document.getElementById("calculatorOverlay");
  const calculatorScreen = document.getElementById("calculatorScreen");
  const calculatorKeys = document.querySelector(".calculator-keys");
  if (!openCalculator || !closeCalculator || !calculatorOverlay || !calculatorScreen || !calculatorKeys) {
    return;
  }

  let expression = "";

  function updateClock() {
    if (!clock) return;
    clock.textContent = new Date().toLocaleTimeString([], {
      hour: "numeric",
      minute: "2-digit",
      second: "2-digit"
    });
  }

  function showCalculator() {
    calculatorOverlay.classList.add("active");
    calculatorOverlay.setAttribute("aria-hidden", "false");
    closeCalculator.focus();
  }

  function hideCalculator() {
    calculatorOverlay.classList.remove("active");
    calculatorOverlay.setAttribute("aria-hidden", "true");
    openCalculator.focus();
  }

  function renderCalculator(value) {
    calculatorScreen.textContent = value || "0";
  }

  function formatResult(value) {
    const [whole, decimal] = String(value).split(".");
    const formattedWhole = Number(whole).toLocaleString("en-US");
    return decimal ? `${formattedWhole}.${decimal}` : formattedWhole;
  }

  function calculate() {
    if (!expression) return;
    try {
      const sanitized = expression.replace(/[^0-9+\-*/.()% ]/g, "");
      const result = Function(`"use strict"; return (${sanitized})`)();
      expression = Number.isFinite(result) ? String(Math.round(result * 100000000) / 100000000) : "";
      renderCalculator(expression ? formatResult(expression) : "Error");
    } catch (error) {
      expression = "";
      renderCalculator("Error");
    }
  }

  openCalculator.addEventListener("click", showCalculator);
  closeCalculator.addEventListener("click", hideCalculator);

  calculatorOverlay.addEventListener("click", (event) => {
    if (event.target === calculatorOverlay) hideCalculator();
  });

  calculatorKeys.addEventListener("click", (event) => {
    const key = event.target.closest("button");
    if (!key) return;

    const value = key.dataset.value;
    const action = key.dataset.action;

    if (action === "clear") {
      expression = "";
    } else if (action === "backspace") {
      expression = expression.slice(0, -1);
    } else if (action === "equals") {
      calculate();
      return;
    } else if (action === "negate") {
      expression = expression ? String(Number(expression) * -1) : "";
    } else if (value) {
      expression += value;
    }

    renderCalculator(expression);
  });

  document.addEventListener("keydown", (event) => {
    if (!calculatorOverlay.classList.contains("active")) return;
    if (event.key === "Escape") hideCalculator();
    if (event.key === "Enter") calculate();
    if (event.key === "Backspace") {
      expression = expression.slice(0, -1);
      renderCalculator(expression);
    }
    if (/^[0-9+\-*/.%()]$/.test(event.key)) {
      expression += event.key;
      renderCalculator(expression);
    }
  });

  updateClock();
  if (clock) {
    setInterval(updateClock, 1000);
  }
})();
