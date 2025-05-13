<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Chat UI</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f2;
    }

    .chat-container {
      max-width: 600px;
      margin: 30px auto;
      background: #fff;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      height: 90vh;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .chat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
      background: gray;
      color: white;
      font-weight: bold;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .back-button {
      background-color: #ffffff22;
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .back-button:hover {
      background-color: #ffffff44;
    }

    .chat-messages {
      flex: 1;
      padding: 16px;
      overflow-y: auto;
      background: #fafafa;
    }

    .message {
      margin-bottom: 15px;
      max-width: 80%;
      padding: 10px 14px;
      border-radius: 20px;
      clear: both;
    }

    .user {
      background: #e0f7fa;
      align-self: flex-end;
      margin-left: auto;
    }

    .admin {
      background: #f1f1f1;
      align-self: flex-start;
      margin-right: auto;
    }

    .chat-input {
      display: flex;
      border-top: 1px solid #ddd;
      padding: 10px;
    }

    .chat-input input {
      flex: 1;
      padding: 10px 15px;
      border-radius: 20px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .chat-input button {
      margin: 10px;
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      background-color: darkgray;
      color: white;
      font-size: 14px;
      cursor: pointer;
    }

    .chat-input button:hover {
      background-color: #8f8c8c;
    }
  </style>
</head>
<body>

<div class="chat-container">
  <div class="chat-header">
    <button class="back-button" onclick="handleBack()">Back</button>
    <span>Customer Support Chat</span>
  </div>

  <div class="chat-messages">
    <!-- Messages will be loaded here -->
  </div>

  <div class="chat-input">
    <input type="text" placeholder="Type your message..." />
    <button>Send</button>
  </div>
</div>

<script>
let userEmail = localStorage.getItem('chat_email');

function checkEmailInDatabase(email) {
  return fetch('check_email.php?email=' + encodeURIComponent(email))
    .then(res => res.json())
    .then(data => data.exists);
}

async function startChat() {
  if (!userEmail) {
    userEmail = prompt("Please enter your email to start the chat:");
    if (!userEmail) {
      alert("Email is required to use the chat.");
      window.history.back();
      return;
    }

    const exists = await checkEmailInDatabase(userEmail);
    if (!exists) {
      alert("Email not found in our system. Please register or contact support.");
      window.history.back();
      return
    }

    localStorage.setItem('chat_email', userEmail);
  }

  setupChat();
}

function setupChat() {
  const input = document.querySelector('.chat-input input');
  const sendBtn = document.querySelector('.chat-input button');
  const messagesDiv = document.querySelector('.chat-messages');

  function loadMessages() {
    fetch('get_messages.php?email=' + encodeURIComponent(userEmail))
      .then(res => res.json())
      .then(data => {
        messagesDiv.innerHTML = '';

        if (data.length === 0) {
          const welcomeDiv = document.createElement('div');
          welcomeDiv.className = 'message user clearfix';
          welcomeDiv.textContent = 'Start a chat by sending a message!';
          messagesDiv.appendChild(welcomeDiv);
        } else {
          data.forEach(msg => {
            const div = document.createElement('div');
            div.className = 'message ' + msg.sender;
            div.textContent = msg.message;
            messagesDiv.appendChild(div);
          });
          messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
      });
  }

  sendBtn.addEventListener('click', () => {
    const message = input.value.trim();
    if (message) {
      fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'sender=user&message=' + encodeURIComponent(message) + '&email=' + encodeURIComponent(userEmail)
      }).then(() => {
        input.value = '';
        loadMessages();
      });
    }
  });

  function handleBack() {
    localStorage.removeItem('chat_email');
    window.history.back();
  }

  document.querySelector('.back-button').addEventListener('click', handleBack);

  setInterval(loadMessages, 3000);
  loadMessages();
}

startChat();
</script>

</body>
</html>
