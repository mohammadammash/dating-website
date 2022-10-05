// selecting signup and login modals interactive buttons
const login_show_button = document.getElementById("login-modal-btn");
const signup_show_button = document.getElementById("signup-modal-btn");
const login_modal = document.getElementById("login-modal");
const signup_modal = document.getElementById("signup-modal");
const close_login_modal = document.getElementById("close-login-modal");
const close_signup_modal = document.getElementById("close-signup-modal");
const submit_login = document.getElementById("login-form");
const submit_signup = document.getElementById("signup-form");
// signup form inputs:
const signup_name = document.getElementById("signup-name");
const signup_email = document.getElementById("signup-email");
const signup_password = document.getElementById("signup-password");
const signup_gender = document.getElementById("signup-gender");
const signup_interested_gender = document.getElementById(
  "signup-interested-gender"
);
const signup_age = document.getElementById("signup-age");
const signup_img_url = document.getElementById("signup-img-url");
const signup_img_show = document.getElementById("signup-img-show");
var base64String; // to hold the image base64 and use in different methods conditionally
//login form inputs:
const login_email = document.getElementById("login-email");
const login_password = document.getElementById("login-password");
const login_submit_button = document.getElementById("login-submit-button");
const signup_submit_button = document.getElementById("signup-submit-button");

// START OF LANDING PAGE GLOBAL FUNCTIONS
//check Current user after login and signup to redirect if found to the home page:
const checkCurrentUser = () => {
  const CurrentUser = localStorage.getItem("user");
  if (CurrentUser) {
    window.location.href = "../home/home.html";
  }
};
//add user to local storage after login and signup:
const addCurrentUser = (response) => {
  const data = response.data.data[0];
  const token = response.data.token;
  localStorage.setItem("user", JSON.stringify(data));
  localStorage.setItem("token", JSON.stringify(token));
};
//get location longitude and latitude:
function getPosition(position) {
  currentLocation = `${position.coords.latitude} ${position.coords.longitude}`;
  if(!currentLocation) return;
  registerNewUser(currentLocation);
}
// END OF LANDING PAGE GLOBAL FUNCTIONS

// START OF EVENT LISTENERS FUNCTIONS
// show image and save url
function updateProfileShown() {
  if (this.files && this.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      base64String = reader.result.replace("data:", "").replace(/^.+,/, "");
      signup_img_show.src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
  }
}
//close login and signup modals on click (X):
const closeModals = () => {
  login_modal.classList.add("display-none");
  signup_modal.classList.add("display-none");
};
//show login modal:
const showLoginModal = () => {
  closeModals();
  login_modal.classList.remove("display-none");
};
//show signup modal:
const showSignupModal = () => {
  closeModals();
  signup_modal.classList.remove("display-none");
};
//submit login modal:
const submitLoginUser = async (e) => {
  e.preventDefault();
  const bodyFormData = new FormData();
  bodyFormData.append("email", login_email.value);
  bodyFormData.append("password", login_password.value);
  const url = `${main_object.baseURL}/login`;
  const response = await main_object.postAPI(url, bodyFormData);
  if (response.data.status === "Success") {
    addCurrentUser(response);
    checkCurrentUser();
  } else {
    console.log("No data dude");
  }
};
//submit signup modal:
async function registerNewUser(location){
  const bodyFormData = new FormData();
  bodyFormData.append("name", signup_name.value);
  bodyFormData.append("email", signup_email.value);
  bodyFormData.append("password", signup_password.value);
  bodyFormData.append("gender", signup_gender.value);
  bodyFormData.append("interested_in", signup_interested_gender.value);
  bodyFormData.append("profile_url", base64String);
  bodyFormData.append("age", signup_age.value);
  bodyFormData.append("bio", "my bio");
  bodyFormData.append("location", location);

  const url = `${main_object.baseURL}/register`;
  const response = await main_object.postAPI(url, bodyFormData);
  if (response.data.status === "Success") {
    addCurrentUser(response);
    checkCurrentUser();
  } else {
    console.log("No data dude");
  }
}
const submitSignupUser = async (e) => {
  e.preventDefault();
  navigator.geolocation.getCurrentPosition(getPosition);
};
// END OF EVENT LISTENERS FUNCTIONS

// START OF EVENT LISTENERS
// login and signup show modal:
login_show_button.addEventListener("click", showLoginModal);
signup_show_button.addEventListener("click", showSignupModal);
//sign up and login submit form:
login_submit_button.addEventListener("click", submitLoginUser);
signup_submit_button.addEventListener("click", submitSignupUser);
//sign up and login close form:
close_login_modal.addEventListener("click", closeModals);
close_signup_modal.addEventListener("click", closeModals);
// whenever we change the image signup url:
signup_img_url.addEventListener("change", updateProfileShown);
//window load events: whenever page loads if there is a user => redirect him/her to the home page
window.addEventListener("load", checkCurrentUser);
// END OF EVENT LISTENERS
