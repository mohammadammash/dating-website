const logout_button = document.getElementById('logout');

// check if there is no user return to landing
const checkCurrentUser = ()=>{
    const user = document.getElementById('user');
    if(!user) window.location.href = '../landing/landing.html';
}

// EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener("load", checkCurrentUser);