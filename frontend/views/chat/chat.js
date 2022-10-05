const logout_button = document.getElementById("logout");
const all_chats_container = document.getElementById("all-chats");
const show_single_chat_container = document.getElementById('show-chat');

//check if there is a user in localstorage, if not return to landing
const checkCurrentUser = () => {
  const user = localStorage.getItem("user");
  if (!user) {
    window.location.href = "../landing/landing.html";
  }
};
// logout user when logout button is clicked
const logoutUser = () => {
  localStorage.clear();
  checkCurrentUser();
};
//get All Messages whenever the page loads
const getAllMessages = async ()=>{
  const api_url = `${main_object.baseURL}/home/chats`;
  const token = localStorage.getItem('token');
  const jwt_token = JSON.parse(token);
  const response = await main_object.getAPI(api_url, jwt_token);
  console.log(response.data);
}
{/* <div class="single-chat eggshell-bg">
    <img src="../../assets/dummy-profile.png">
    <div class="user-info">
    <p class="size-20"><span class='name'>Hadi </span><span class='gender'>~male</span></p>
    <p class="last-msg">Okay, meet you arou...</p>
</div>
<button class="btn btn-md">Show</button>
</div> */}

// START OF EVENT LISTENERS
logout_button.addEventListener("click", logoutUser);
//on window load
window.onload = ()=>{
  //check if there is a user first
  checkCurrentUser();
  //get all messages
  getAllMessages();
}
