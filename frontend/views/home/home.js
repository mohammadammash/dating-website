const logout_button = document.getElementById("logout");

// check if there is no user return to landing
const checkCurrentUser = () => {
  const user = localStorage.getItem('user');
  if(!user) window.location.href = '../landing/landing.html';
};
// logout current user:
const logoutUser = () => {
  localStorage.clear();
  checkCurrentUser();
};

// EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener("load", checkCurrentUser);
