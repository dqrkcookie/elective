<?php
// Redirect if no email is provided
if (!isset($_GET['email']) || empty($_GET['email'])) {
  header('Location: index.php?error=no_email');
  exit;
}

$email = htmlspecialchars($_GET['email']); // sanitize output for HTML
?>
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

    .email-info {
      font-size: 0.8em;
      opacity: 0.9;
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
      display: inline-block;
      clear: both;
    }

    .user {
      background: #f1f1f1;
      float: left;
      border-top-left-radius: 0;
    }

    .admin {
      background: #cce5ff;
      float: right;
      border-top-right-radius: 0;
    }

    .clearfix::after {
      content: "";
      clear: both;
      display: table;
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
      margin-left: 10px;
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
    <button class="back-button" onclick="window.history.back()">Back</button>
    <span>Customer Support Chat</span>
    <span class="email-info"><?php echo $email; ?></span>
  </div>

  <div class="chat-messages" id="chatMessages">
    <!-- Messages will be loaded dynamically -->
  </div>

  <div class="chat-input">
    <input type="text" placeholder="Type your message..." autocomplete="off" />
    <button>Send</button>
  </div>
</div>

<script>
const input = document.querySelector('.chat-input input');
const sendBtn = document.querySelector('.chat-input button');
const messagesDiv = document.getElementById('chatMessages');
const customerEmail = '<?php echo $email; ?>';

function loadMessages() {
  fetch('get_messages.php?email=' + encodeURIComponent(customerEmail) + '&t=' + Date.now())
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
          div.className = 'message ' + (msg.sender === 'admin' ? 'admin' : 'user') + ' clearfix';
          div.textContent = msg.message;
          messagesDiv.appendChild(div);
        });
      }

      messagesDiv.scrollTop = messagesDiv.scrollHeight;
    })
    .catch(error => {
      console.error('Error loading messages:', error);
    });
}

sendBtn.addEventListener('click', () => {
  const message = input.value.trim();
  if (message) {
    sendBtn.disabled = true;

    fetch('send_message.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'email=' + encodeURIComponent(customerEmail) + '&message=' + encodeURIComponent(message) + '&sender=admin'
    })
    .then(() => {
      input.value = '';
      loadMessages();
    })
    .catch(error => {
      console.error('Error sending message:', error);
    })
    .finally(() => {
      sendBtn.disabled = false;
    });
  }
});

input.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    sendBtn.click();
  }
});

loadMessages();
setInterval(loadMessages, 3000);
</script>

</body>
</html>
