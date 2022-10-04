const logout_button = document.getElementById("logout");

const checkCurrentUser = () => {
  const user = localStorage.getItem("user");
  if (!user) {
    window.location.href = "../landing/landing.html";
  }
};

const logoutUser = () => {
  localStorage.clear();
  checkCurrentUser();
};

// START OF EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener('load', checkCurrentUser);
