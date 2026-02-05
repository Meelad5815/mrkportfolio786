const themeToggle = document.getElementById("themeToggle");
const contactForm = document.getElementById("contactForm");
const prefersDark = window.matchMedia("(prefers-color-scheme: dark)");

const setTheme = (mode) => {
  document.body.classList.toggle("dark", mode === "dark");
  themeToggle.querySelector(".toggle-icon").textContent = mode === "dark" ? "☀" : "☾";
  themeToggle.querySelector(".toggle-text").textContent = mode === "dark" ? "Light" : "Dark";
};

const storedTheme = localStorage.getItem("theme");
const initialTheme = storedTheme || (prefersDark.matches ? "dark" : "light");
setTheme(initialTheme);

prefersDark.addEventListener("change", (event) => {
  if (!localStorage.getItem("theme")) {
    setTheme(event.matches ? "dark" : "light");
  }
});

themeToggle.addEventListener("click", () => {
  const isDark = document.body.classList.contains("dark");
  const nextTheme = isDark ? "light" : "dark";
  localStorage.setItem("theme", nextTheme);
  setTheme(nextTheme);
});

contactForm.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(contactForm);
  const name = formData.get("name");
  const email = formData.get("email");
  const message = formData.get("message");

  const subject = encodeURIComponent(`Portfolio inquiry from ${name}`);
  const body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\n\n${message}`);
  window.location.href = `mailto:hafizmuhammadmeeladraza@gmail.com?subject=${subject}&body=${body}`;
});
