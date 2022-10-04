const logout_button = document.getElementById("logout");
//home container to add users to:
const home_container = document.getElementById("home-container");

// START OF WINDOW USER FUNCTIONS
// check if there is no user return to landing
const checkCurrentUser = () => {
  const user = localStorage.getItem("user");
  if (!user) window.location.href = "../landing/landing.html";
};
// logout current user:
const logoutUser = () => {
  localStorage.clear();
  checkCurrentUser();
};
// END OF WINDOW USER FUNCTIONS

// START OF ICONS EVENT LISTENERS FUNCTIONS
function AddToFavorites() {
  const card = this.parentNode.parentNode;
  const favorited_id = card.getAttribute("data-value");
  console.log(favorited_id);
}
function AddToBlocked() {
  const card = this.parentNode.parentNode;
  const blocked_id = card.getAttribute("data-value");
  console.log(blocked_id);
}
function chatWith() {
  const card = this.parentNode.parentNode;
  const receiver_id = card.getAttribute("data-value");
  console.log(receiver_id);
}
// END OF ICONS EVENT LISTENERS FUNCTIONS

// START OF GETTING ALLUSERS, APPENDING USERS, ADDING USER EVENT LISTENERS
// add card event listeners after appending the card:
const addCardEventListeners = () => {
  const favorite_icons = document.querySelectorAll(".favorite");
  const chat_icons = document.querySelectorAll(".chat");
  const block_icons = document.querySelectorAll(".block");

  for (let fav of favorite_icons) fav.addEventListener("click", AddToFavorites);
  for (let chat of chat_icons) chat.addEventListener("click", chatWith);
  for (let block of block_icons) block.addEventListener("click", AddToBlocked);
};
// add Users To HTML:
const addUsersToHTML = (users) => {
  const appendToContainer = (user) => {
    const userHTML = `<div class="card eggshell-bg" data-value=${user.id}>
    <img class="profile-img" src="${user.profile_url}">
    <div>
        <p>Name: <span class="name">${user.name}</span></p>
        <p>Age: <span class="age">${user.age}</span></p>
    </div>
    <div>
        <p>Gender: <span class="gender">${user.gender}</span></p>
        <p>Interested in: <span class="interested-in-gender">${user.interested_in}</span></p>
    </div>
    <p class="bio">${user.bio}</p>;
    <div class="logos">
    <img src="../../assets/icons8-favorite-50 (1).png" class="favorite">
        <img src="../../assets/icons8-chat-room-50.png" class="chat">
        <img src="../../assets/icons8-block-50.png" class="block">
    </div>
</div>`;
    home_container.innerHTML += userHTML;
  };

  for (let user of users) appendToContainer(user);
  addCardEventListeners();
};
// FETCH ALL USERS:
const getAllUsers = async () => {
  const api_url = `${main_object.baseURL}/home`;
  const token = localStorage.getItem("token");
  if (token) jwt_token = JSON.parse(token);

  const response = await main_object.getAPI(api_url, jwt_token);
  //   const data = response.data.data;
  if (response.data.status === "Success") {
    addUsersToHTML(response.data.data);
  } else {
    console.log("No data dude");
  }
};
// END OF GETTING ALLUSERS, APPENDING USERS, ADDING USER EVENT LISTENERS

// START OF EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener("load", checkCurrentUser);
window.addEventListener("load", getAllUsers);
// END OF EVENT LISTENERS
