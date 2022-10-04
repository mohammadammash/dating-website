const logout_button = document.getElementById("logout");
//home container to add users to:
const home_container = document.getElementById("home-container");

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
// add Users To HTML:
const addUsersToHTML = (users) => {
  const appendToContainer = (user) => {
    const userHTML = `<div class="card eggshell-bg">
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

// START OF EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
window.addEventListener("load", checkCurrentUser);
window.addEventListener("load", getAllUsers);
// END OF EVENT LISTENERS

