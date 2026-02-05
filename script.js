const themeToggle = document.querySelector(".theme-toggle");
const navToggle = document.querySelector(".nav-toggle");
const navLinks = document.querySelector(".nav-links");
const sections = document.querySelectorAll("section, .hero");

const savedTheme = localStorage.getItem("theme");
if (savedTheme) {
  document.documentElement.setAttribute("data-theme", savedTheme);
  themeToggle.querySelector(".theme-toggle__icon").textContent =
    savedTheme === "dark" ? "☀️" : "🌙";
}

themeToggle.addEventListener("click", () => {
  const currentTheme =
    document.documentElement.getAttribute("data-theme") === "dark"
      ? "light"
      : "dark";
  document.documentElement.setAttribute("data-theme", currentTheme);
  localStorage.setItem("theme", currentTheme);
  themeToggle.querySelector(".theme-toggle__icon").textContent =
    currentTheme === "dark" ? "☀️" : "🌙";
});

navToggle.addEventListener("click", () => {
  const isVisible = navLinks.getAttribute("data-visible") === "true";
  navLinks.setAttribute("data-visible", (!isVisible).toString());
});

document.addEventListener("click", (event) => {
  if (!navLinks.contains(event.target) && !navToggle.contains(event.target)) {
    navLinks.setAttribute("data-visible", "false");
  }
});

const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in");
        observer.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.15 }
);

sections.forEach((section) => observer.observe(section));
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
