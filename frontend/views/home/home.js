const logout_button = document.getElementById("logout");
//home container to add users to:
const home_container = document.getElementById('home-container');

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

// FETCH ALL USERS:
const getAllUsers = ()=>{
    console.log("let's get users");
}

// START OF EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener("load", checkCurrentUser);
window.addEventListener('load',getAllUsers);
// END OF EVENT LISTENERS

{/* <div class="card eggshell-bg">
    <img class="profile-img" src="../../assets/dummy-profile.png">
    <div>
        <p>Name: <span class="name">Hadi</span></p>
        <p>Age: <span class="age">23</span></p>
    </div>
    <div>
        <p>Gender: <span class="gender">Male</span></p>
        <p>Interested in: <span class="interested-in-gender">Female</span></p>
    </div>
    <p class="bio">Hi! My name is Mark, and I am brand new to online dating. While I’m still figuring this all out, here’s something I know for sure—I’m excited to be here!</p>
    <div class="logos">
        <img src="../../assets/icons8-favorite-50 (1).png" class="favorite">
        <img src="../../assets/icons8-chat-room-50.png" class="chat">
        <img src="../../assets/icons8-block-50.png" class="block">
    </div>
</div>  */}