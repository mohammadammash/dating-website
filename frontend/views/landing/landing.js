// selecting signup and login modals interactive buttons
const login_show_button = document.getElementById('login-modal-tn');
const signup_show_button = document.getElementById("login-modal-btn");
const login_modal = document.getElementById('login-modal');
const signup_modal = document.getElementById("signup-modal");
const close_login_modal = document.getElementById('close-login-modal');
const close_signup_modal = document.getElementById("close-signup-modal");
const submit_login = document.getElementById('login-form');
const submit_signup = document.getElementById("signup-form");
// signup form inputs:
const signup_name = document.getElementById('signup-name');
const signup_email = document.getElementById('signup-email');
const signup_password = document.getElementById('signup-password');
const signup_gender = document.getElementById('signup-gender');
const signup_interested_gender = document.getElementById('signup-interested-gender');
const signup_age = document.getElementById('signup-age');
const signup_img_url = document.getElementById('signup-img-url');
const signup_img_show = document.getElementById("signup-img-show");
//login form inputs:
const login_email = document.getElementById('login-email'); 
const login_password = document.getElementById("login-password");


// START OF EVENT LISTENERS
//sign up and login submit form:
submit_login.addEventListener('submit',loginUser);
submit_signup.addEventListener("submit", signupUser);
//sign up and login close form:
close_login_modal.addEventListener('close',closeLoginModal);
close_signup_modal.addEventListener("close", closeSignupModal);
// whenever we change the image signup url:
signup_img_url.addEventListener('change',updateProfileShown);
// END OF EVENT LISTENERS