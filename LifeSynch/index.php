<?php

session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LifeSynch - Wellness App</title>

  <style>
      * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #4c780c;
            color: white;
            width: 100%;
            position: relative;
            top: 0;
        }

        .hero {
          position: relative;
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          text-align: center;
          color: white;
          overflow: hidden;
        }

        .video-container {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: -1;
        }

        .video-container video {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }

        .video-overlay {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
          z-index: 1;
        }

        .btn-primary {
          display: inline-block;
          padding: 0.8rem 1.5rem;
          background-color: #4c780c;
          color: white;
          border: none;
          border-radius: 5px;
          font-size: 1rem;
          cursor: pointer;
          transition: background 0.3s ease-in-out;
        }

        .btn-primary:hover {
          background-color: #3a5e0a;
        }

        .modal {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.6);
          justify-content: center;
          align-items: center;
          z-index: 1000;
        }

        .modal-content {
          background: white;
          padding: 2rem;
          text-align: center;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .modal-buttons a {
          display: inline-block;
          margin: 0.5rem;
          padding: 0.8rem 1.2rem;
          background-color: #4c780c;
          color: white;
          border-radius: 5px;
          text-decoration: none;
          font-weight: bold;
        }

        .close-modal {
          position: absolute;
          top: 10px;
          right: 10px;
          font-size: 1.5rem;
          cursor: pointer;
        }

        section#about-us {
          text-align: center;
          padding: 2rem;
          background-color: #f4f4f4;
        }

        .about-container {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          gap: 1.5rem;
        }

        .about-item {
          flex: 1 1 22%;
          max-width: 22%;
          background-color: white;
          border: 1px solid #ddd;
          border-radius: 8px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          transition: transform 0.3s ease-in-out;
        }

        .about-item:hover {
          transform: scale(1.05);
        }

        .about-item img {
          width: 100%;
          height: 150px;
          object-fit: cover;
          border-radius: 8px 8px 0 0;
        }

        .about-content h3 {
          margin: 1rem 0 0.5rem;
          font-size: 1.2rem;
          color: #4c780c;
        }

        .about-content p {
          font-size: 1rem;
          color: #666;
          padding: 0 1rem 1rem;
        }

        .chatbox-label {
          position: fixed;
          bottom: 100px;
          right: 20px;
          background-color: rgba(0, 0, 0, 0.5);
          color: white;
          padding: 5px;
          border-radius: 5px;
          font-size: 14px;
          text-align: center;
        }

        .logo a {
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1.5rem;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            margin-bottom: 40px;
        }

        
        .error-message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        p {
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        #chatbox-iframe {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            height: 400px;
            border: none; 
            background: transparent; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0);
            border-radius: 10px;
            z-index: 1000;
        }

        .chatbox-label {
            position: fixed;
            bottom: 100px;
            right: 20px;
            font-size: 14px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 5px;
        }

        section#about-us {
          text-align: center;
          padding: 2rem;
          background-color: #f4f4f4;
        }

        .about-container {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          gap: 1.5rem;
          margin-top: 2rem;
        }

        .about-item {
          flex: 1 1 calc(25% - 1.5rem);
          max-width: calc(25% - 1.5rem);
          text-align: center;
          background-color: white;
          border: 1px solid #ddd;
          border-radius: 8px;
          padding: 1rem;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          transition: transform 0.3s ease-in-out;
        }

        .about-item:hover {
          transform: scale(1.05);
        }

        .about-item img {
          width: 100%;
          height: 150px;
          object-fit: cover;
          border-radius: 8px 8px 0 0;
        }

        .about-content h3 {
          margin: 1rem 0 0.5rem;
          font-size: 1.2rem;
          color: #4c780c;
        }

        .about-content p {
          font-size: 1rem;
          color: #666;
        }

        footer {
          text-align: center;
          padding: 1rem;
          background-color: #4c780c;
          color: white;
          margin-top: auto;
        }
    

  </style>

</head>
<body>
  <nav>
    <div class="logo">
      <a href="#">LifeSynch</a>
    </div>
    <ul class="nav-links">
      <li><a href="login.php">Login</a></li>
      <li><a href="#about-us">About Us</a></li>
    </ul>
  </nav>

  <header class="hero">
    <div class="video-container">
      <video autoplay muted loop>
        <source src="Backgroundvid.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="video-overlay"></div>
    </div>
  
  <div class="hero-content">
    <h1>Welcome to LifeSynch</h1>
    <p>Your personal wellness assistant to keep track of sleep, hydration, exercise, and more!</p>
    <button class="btn-primary" id="get-started-btn">Get Started</button>
  </div>
</header>

  <div id="get-started-modal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Welcome to LifeSynch</h2>
      <p>Please choose an option to continue:</p>
      <div class="modal-buttons">

      <?php
            if (isset($_SESSION['userId'])) {
                echo "<a href='login.php' class='btn-secondary'>Login</a>";
                echo "<li><a href='dashboard.php'><i class='icon-home'></i>Dashboard</a></li>";
                echo "<li><a href='profile.php'><i class='icon-profile'></i>Profile</a></li>";
                echo "<li><a href='logout_action.php'><i class='icon-logout'></i>Logout</a></li>";
            }
            else{

              echo "<a href='login.php' class='btn-secondary'>Login</a>";

              echo "<a href='register.php' class='btn-secondary'>Register</a>";
            }

  

            if (isset($_SESSION['roleId']) && $_SESSION['roleId'] == 2) {
                echo "<li><a href='adminDashboard.php'><i class='icon-profile'></i>Admin Dashboard</a></li>";
            }

        ?>

        
       
      </div>
    </div>
  </div>

  <section id="about-us" class="about-us">
    <h2>About Us</h2>
    <div class="about-container">
      <div class="about-item">
        <img src="sleep.jpg" alt="Track Sleep">
        <div class="about-content">
          <h3>Track Sleep</h3>
          <p>Monitor your sleep patterns and improve your quality of rest.</p>
        </div>
      </div>
      <div class="about-item">
        <img src="hydration.jpg" alt="Hydration Reminders">
        <div class="about-content">
          <h3>Hydration Reminders</h3>
          <p>Get reminders to stay hydrated throughout the day.</p>
        </div>
      </div>
      <div class="about-item">
        <img src="exercice.jpg" alt="Exercise Goals">
        <div class="about-content">
          <h3>Exercise Goals</h3>
          <p>Set exercise goals and stay motivated with daily reminders.</p>
        </div>
      </div>
      <div class="about-item">
        <img src="motivation.jpg" alt="Motivational Quotes">
        <div class="about-content">
          <h3>Motivational Quotes</h3>
          <p>Receive inspiring messages to keep you going every day.</p>
        </div>
      </div>
    </div>
  </section>

  <div class="chatbox-label">
    <p>Need help? <br>Chat with us!</p>
  </div>
  <iframe id="chatbox-iframe" src="chatbox.php" title="Contact Us Chatbox"></iframe>

  
  <footer>
    <p>&copy; 2024 LifeSynch. All rights reserved.</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
    const getStartedBtn = document.getElementById("get-started-btn");
    const modal = document.getElementById("get-started-modal");
    const closeModal = document.querySelector(".close-modal");

    getStartedBtn.addEventListener("click", () => {
      modal.style.display = "flex";
    });

    closeModal.addEventListener("click", () => {
      modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
      }
    });
  });
  </script>
</body>

</html>
