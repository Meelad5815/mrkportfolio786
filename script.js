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
