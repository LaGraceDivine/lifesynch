<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chatbox</title>
  <style>

    #chat-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #4c780c;
      color: white;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

  
    #chat-container {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 300px;
      z-index: 1000;
      font-family: Arial, sans-serif;
      display: none;
    }

    #chat-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background: white;
      overflow: hidden;
    }

    /* Chat Header */
    #chat-header {
      background-color: #4c780c;
      color: white;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    #chat-header h3 {
      margin: 0;
      font-size: 16px;
    }

    #chat-header button {
      background: none;
      border: none;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

  
    #chat-content {
      padding: 10px;
    }

    #chat-content input,
    #chat-content button {
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    
    #chat-content button {
      background-color: #4c780c;
      color: white;
      border: none;
      cursor: pointer;
    }

    #chat-content button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div id="chat-icon" onclick="toggleChat()">
    💬
  </div>


  <div id="chat-container">
    <div id="chat-box">
      <div id="chat-header">
        <h3>Contact Us</h3>
        <button onclick="toggleChat()">X</button>
      </div>
      <div id="chat-content">
        <input type="text" id="user-name" placeholder="Your Name" required />
        <input type="email" id="user-email" placeholder="Your Email" required />
        <textarea id="user-question" placeholder="Your Question" rows="4" required></textarea>
        <button id="send-message" onclick="sendMessage()">Send</button>
        <p id="response-message" style="display: none; color: green;"></p>
      </div>
    </div>
  </div>

  <script>
    
    function toggleChat() {
      const chatContainer = document.getElementById("chat-container");
      const chatIcon = document.getElementById("chat-icon");

      if (chatContainer.style.display === "none" || chatContainer.style.display === "") {
        chatContainer.style.display = "block";
        chatIcon.style.display = "none";
      } else {
        chatContainer.style.display = "none";
        chatIcon.style.display = "flex";
      }
    }

  
    function sendMessage() {
      const name = document.getElementById("user-name").value;
      const email = document.getElementById("user-email").value;
      const question = document.getElementById("user-question").value;
      const responseMessage = document.getElementById("response-message");

      if (!name || !email || !question) {
        alert("Please fill out all fields.");
        return;
      }

      
      fetch("chatbox_action.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ name, email, question }),
      })
        .then((response) => response.json())
        .then((data) => 
        {
          console.log(data);
          if (data.success) 
          {
            responseMessage.textContent = "Your message has been sent. We will get back to you!";
            responseMessage.style.display = "block";

            
            document.getElementById("user-name").value = "";
            document.getElementById("user-email").value = "";
            document.getElementById("user-question").value = "";
          } else {
            alert("Failed to send message. Please try again later.");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Failed to send message. Please try again later.");
        });
    }
  </script>
</body>
</html>
