const themeToggle = document.getElementById("themeToggle");
const contactForm = document.getElementById("contactForm");
const prefersDark = window.matchMedia("(prefers-color-scheme: dark)");

const setTheme = (mode) => {
  document.body.classList.toggle("dark", mode === "dark");
  if (!themeToggle) {
    return;
  }
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

if (themeToggle) {
  themeToggle.addEventListener("click", () => {
    const isDark = document.body.classList.contains("dark");
    const nextTheme = isDark ? "light" : "dark";
    localStorage.setItem("theme", nextTheme);
    setTheme(nextTheme);
  });
}

if (contactForm) {
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
}

const adminAccessForm = document.getElementById("adminAccessForm");
const adminStatus = document.getElementById("adminStatus");
const disableAdmin = document.getElementById("disableAdmin");
const postForm = document.getElementById("postForm");
const postList = document.getElementById("postList");
const grantForm = document.getElementById("grantForm");
const adminList = document.getElementById("adminList");

const adminState = {
  enabled: localStorage.getItem("adminAccess") === "true",
  passcode: "admin123",
};

const updateAdminStatus = () => {
  if (!adminStatus) {
    return;
  }
  adminStatus.textContent = adminState.enabled
    ? "Admin access enabled. You can upload posts and grant access."
    : "Admin access is currently disabled.";
  if (postForm) {
    postForm.querySelectorAll("input, textarea, button").forEach((el) => {
      el.disabled = !adminState.enabled;
    });
  }
  if (grantForm) {
    grantForm.querySelectorAll("input, button").forEach((el) => {
      el.disabled = !adminState.enabled;
    });
  }
};

const renderPosts = () => {
  if (!postList) {
    return;
  }
  const posts = JSON.parse(localStorage.getItem("adminPosts") || "[]");
  postList.innerHTML = posts
    .map(
      (post) => `
        <article class="post-item">
          <h3>${post.title}</h3>
          <p class="muted">${post.description}</p>
          ${post.image ? `<img src="${post.image}" alt="${post.title}" />` : ""}
        </article>
      `
    )
    .join("");
};

const renderAdmins = () => {
  if (!adminList) {
    return;
  }
  const admins = JSON.parse(localStorage.getItem("adminUsers") || "[]");
  adminList.innerHTML = admins.map((email) => `<li>• ${email}</li>`).join("");
};

if (adminAccessForm) {
  adminAccessForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const passcode = new FormData(adminAccessForm).get("passcode");
    adminState.enabled = passcode === adminState.passcode;
    localStorage.setItem("adminAccess", adminState.enabled);
    adminAccessForm.reset();
    updateAdminStatus();
  });
}

if (disableAdmin) {
  disableAdmin.addEventListener("click", () => {
    adminState.enabled = false;
    localStorage.setItem("adminAccess", "false");
    updateAdminStatus();
  });
}

if (postForm) {
  postForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(postForm);
    const post = {
      title: formData.get("title"),
      description: formData.get("description"),
      image: formData.get("image"),
    };
    const posts = JSON.parse(localStorage.getItem("adminPosts") || "[]");
    posts.unshift(post);
    localStorage.setItem("adminPosts", JSON.stringify(posts));
    postForm.reset();
    renderPosts();
  });
}

if (grantForm) {
  grantForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const email = new FormData(grantForm).get("email");
    const admins = JSON.parse(localStorage.getItem("adminUsers") || "[]");
    admins.unshift(email);
    localStorage.setItem("adminUsers", JSON.stringify(admins));
    grantForm.reset();
    renderAdmins();
  });
}

updateAdminStatus();
renderPosts();
renderAdmins();

const loginForm = document.getElementById("loginForm");
const signupForm = document.getElementById("signupForm");

if (loginForm) {
  loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    alert("Demo login successful. Connect to backend for real authentication.");
  });
}

if (signupForm) {
  signupForm.addEventListener("submit", (event) => {
    event.preventDefault();
    alert("Demo signup successful. Connect to backend for real authentication.");
  });
}
