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
//axios:

// START OF EVENT LISTENERS FUNCTIONS
// show image and save url
function updateProfileShown(){
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
const closeModals=()=> {
  login_modal.classList.add("display-none");
  signup_modal.classList.add("display-none");
}
//show login modal:
const showLoginModal =()=> {
  closeModals();
  login_modal.classList.remove("display-none");
}
//show signup modal:
const showSignupModal = ()=> {
  closeModals();
  signup_modal.classList.remove("display-none");
}
//submit login modal:
const submitLoginUser = (e)=>{
    e.preventDefault();
    // const [email, password] = [login_email.value, login_password.value];
    // const data = main_object.postAPI(`${main_object.baseURL}/login`, {email, password});
    // return data;
}
//submit signup modal:
const submitSignupUser = (e)=>{
    e.preventDefault();
}
// END OF EVENT LISTENERS FUNCTIONS


// START OF EVENT LISTENERS
// login and signup show modal:
login_show_button.addEventListener("click", showLoginModal);
signup_show_button.addEventListener("click", showSignupModal);
//sign up and login submit form:
submit_login.addEventListener("submit", submitLoginUser);
submit_signup.addEventListener("submit",submitSignupUser);
//sign up and login close form:
close_login_modal.addEventListener("click", closeModals);
close_signup_modal.addEventListener("click", closeModals);
// whenever we change the image signup url:
signup_img_url.addEventListener("change", updateProfileShown);
// END OF EVENT LISTENERS
