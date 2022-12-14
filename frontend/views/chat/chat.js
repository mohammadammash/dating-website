const logout_button = document.getElementById("logout");
const all_chats_container = document.getElementById("all-chats");
const show_single_chat_content = document.getElementById("show-content");
const show_single_chat_img = document.getElementById("show-img");
const show_single_chat_name = document.getElementById("show-name");
const show_single_chat_container = document.getElementById("single-chat");
const no_chat_displayed_title = document.getElementById(
  "no-single-chat-display"
);
const send_message_button = document.getElementById("send-message-button");
const send_message_content = document.getElementById("send-message-content");

// ------START OF GENERAL PAGE FUNCTIONS------
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
// check if there is a chat to open"
const checkIfThereIsChatToOpen = async () => {
  const receiver = JSON.parse(localStorage.getItem("chat_with"));
  if (!receiver) return;

  send_message_content.value = ""; //empty the message input /make sure its empty
  const img_url = receiver.profile_url;
  const name = receiver.name;
  const shown_id = receiver.id;
  const api_url = `${main_object.baseURL}/home/chats/${shown_id}`;
  const jwt_token = JSON.parse(localStorage.getItem("token"));
  const response = await main_object.getAPI(api_url, jwt_token);
  if (response.data.status === "Success") {
    appendChatHTML(response.data.messages, name, img_url, shown_id);
  } else {
    logoutUser();
  }
};
// ------END OF GENERAL PAGE FUNCTIONS------

// ------START OF LEFT SECTION CHATS------
//add current sent message directly to html content, before adding to db for better UX
//post new message to database:
const postMessageToDatabase = (receiver_id, message) => {
  const api_url = `${main_object.baseURL}/home/chats/${receiver_id}`;
  const data = {
    message: message,
  };
  const jwt_token = JSON.parse(localStorage.getItem("token"));
  main_object.postAPI(api_url, data, jwt_token);
};
//show message sent in html
const addMessageSentToHTMLDirectly = (message) => {
  //get created_at date instantly
  const currentdate = new Date();
  const datetime =
    currentdate.getDate() +
    "/" +
    (currentdate.getMonth() + 1) +
    "/" +
    currentdate.getFullYear() +
    " " +
    currentdate.getHours() +
    ":" +
    currentdate.getMinutes() +
    ":" +
    currentdate.getSeconds();
  //show single chat directly to chat
  show_single_chat_content.innerHTML += `<div class="sent-message"><p class="text">${message}<span class='date'>${datetime}</span></p></div>`;
};
//send message:
const sendMessage = (e) => {
  show_single_chat_content.innerHTML = "";
  if (!send_message_content.value) return;
  const message = send_message_content.value;
  send_message_content.value = ""; //empty the message input
  const receiver_id =
    e.target.parentNode.parentNode.children[1].getAttribute("data-value");
  const sender_id = JSON.parse(localStorage.getItem("user")).id;
  addMessageSentToHTMLDirectly(message);
  postMessageToDatabase(receiver_id, message);
};
//append single chat message to HTML:
const appendChatHTML = (messages, name, img_url, shown_id) => {
  no_chat_displayed_title.classList.add("display-none");
  show_single_chat_img.src = img_url;
  show_single_chat_name.textContent = name;
  show_single_chat_content.innerHTML = "";
  show_single_chat_content.setAttribute("data-value", shown_id);
  if (messages.length <= 0) show_single_chat_content.innerHTML = "<h2>No Messages</h2>";
  else show_single_chat_content.innerHTML = "";
  for (let message of messages) {
    if (message.receiver_id !== parseInt(shown_id)) {
      show_single_chat_content.innerHTML += `<div class="received-message"><p class="text">${message.text}<span class='date'>${message.created_at}</span></p></div>`;
    } else {
      show_single_chat_content.innerHTML += `<div class="sent-message"><p class="text">${message.text}<span class='date'>${message.created_at}</span></p></div>`;
    }
  }
  show_single_chat_container.classList.remove("display-none");
};
//show single chat:
const showSingleChat = async (e) => {
  send_message_content.value = ""; //empty the message input
  const chat_card = e.target.parentNode;
  const img_url = chat_card.children[0].src;
  const name = chat_card.children[1].children[0].children[0].textContent;
  const shown_id = chat_card.getAttribute("data-value");
  const api_url = `${main_object.baseURL}/home/chats/${shown_id}`;
  const jwt_token = JSON.parse(localStorage.getItem("token"));
  const response = await main_object.getAPI(api_url, jwt_token);
  if (response.data.status === "Success") {
    appendChatHTML(response.data.messages, name, img_url, shown_id);
  } else {
    logoutUser();
  }
};
// add unique user to left chat box with the message: //show only 5 first charcs of last message
const addChat = (message, id) => {
  const { sender_id, receiver_id, name, profile_url, text, gender } = message;
  if (sender_id !== id) id = sender_id;
  else if (receiver_id !== id) id = receiver_id;
  const htmlChat = `<div class="single-chat eggshell-bg" data-value='${id}'>
                        <img src="${profile_url}">
                        <div class="user-info">
                          <p class="size-20"><span class='name'>${name}</span><span class='gender'>~${gender}</span></p>
                          <p class="last-msg">${text.slice(0, 5)}...</p>
                        </div>
                        <button class="btn btn-md show-chat-button">Show</button>
                      </div>`;
  all_chats_container.innerHTML += htmlChat;
  all_chats_container.innerHTML += `<br>`;
};

//const remove repeated users: //by looping from end of results(latest messages first and getting only last message)
const showEachUserOnce = (messages, unique_ids) => {
  const currentUser = localStorage.getItem("user");
  const id = JSON.parse(currentUser).id;
  for (let i = messages.length - 1; i >= 0; i--) {
    const message = messages[i];
    const sender_id = message.sender_id;
    const receiver_id = message.receiver_id;

    if (sender_id === id && !unique_ids.has(receiver_id)) {
      addChat(message, id);
      unique_ids.add(receiver_id);
    } else if (receiver_id === id && !unique_ids.has(sender_id)) {
      addChat(message, id);
      unique_ids.add(sender_id);
    }
  }

  return unique_ids;
};
//get All Messages whenever the page loads
const getAllMessages = async () => {
  const api_url = `${main_object.baseURL}/home/chats`;
  const token = localStorage.getItem("token");
  const jwt_token = JSON.parse(token);
  const response = await main_object.getAPI(api_url, jwt_token);
  const unique_ids = new Set();
  showEachUserOnce(response.data.messages_sent, unique_ids);
  showEachUserOnce(response.data.messages_received, unique_ids);
  //after getting all unique chats and showing them at left section, add show Event listeners:
  const showButtons = document.querySelectorAll(".show-chat-button");
  for (let btn of showButtons) btn.addEventListener("click", showSingleChat);
};
// ------END OF LEFT SECTION CHATS------

// ------START OF EVENT LISTENERS------
logout_button.addEventListener("click", logoutUser);
//on window load
window.onload = () => {
  //check if there is a user first
  checkCurrentUser();
  //get all messages
  getAllMessages();
  //check if we are sent by clicking on chat icon of specific user(check local storage for 'chat_with' key)
  checkIfThereIsChatToOpen();
};
send_message_button.addEventListener("click", sendMessage);
// ------END OF EVENT LISTENERS------
