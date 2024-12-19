<?php
session_start();
$userId = $_SESSION["userId"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Sleep Tracker</title>

    <style> 
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgba(0, 255, 0, 0.1);
        }

        header {
            background-color: #4CAF50;
            padding: 20px;
            text-align: center;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: : 50px;
            padding: 20px;
            align-items: center;
            height: calc(80vh - 80px); 
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1001;
        }

        .navbar {
            width: 50px;
            transition: width 0.3s ease;
        }

        .navbar.open {
            width: 200px;
        }

        .navbar .hamburger {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #4CAF50;
            margin: 5px 0;
            transition: transform 0.3s ease;
        }

        .navbar.open .hamburger {
            transform: rotate(90deg);
        }

        .navbar {
            background-color: #4CAF50;
            color: #fff;
            position: fixed;
            top: 0;
            left: -200px;
            height: 100%;
            width: 200px;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            transition: left 0.3s ease-in-out;
        }

        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .navbar ul li {
            margin: 15px 0;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease-in-out;
        }

        .navbar ul li a:hover {
            background-color: #4CAF50;
            transform: translateY(-2px);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .sub-dashboard {
            background-color: #fff;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sub-dashboard nav ul {
            list-style-type: none;
            display: flex;
            justify-content: space-evenly;
            padding: 10px 0;
        }
        .sub-dashboard nav ul li {
            margin: 10px 0;
        }

        .sub-dashboard nav ul li a {
            text-decoration: none;
            color: #555;
            font-size: 14px;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sub-dashboard nav ul li a:hover {
            background-color: #4CAF50;
            color: white;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 80px;
            margin-bottom: 100px;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #2eb11f;
            margin-bottom: 10px;
        }

        header p {
            font-size: 16px;
            color: #666;
        }

        h3 {
            margin-bottom: 10px;
        }

        .form-container {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="time"] {
            padding: 10px;
            width: 200px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .sleep-summary {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background-color: #f3f3f3;
            width: 100%;
            height: 30px;
            border-radius: 15px;
            margin-top: 10px;
        }

        #progress {
            height: 100%;
            background-color: #4CAF50;
            border-radius: 15px;
        }

        @media screen and (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                align-items: center;
            }

            .navbar ul li {
                margin-bottom: 10px;
            }

            .sub-dashboard nav ul {
                flex-direction: column;
                align-items: center;
            }
        }

        .navbar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1001;
        }
    </style> 
    </script>
</head>
<body>

    <div class="navbar-toggle" onclick="toggleNavbar()">
        <i class="fas fa-bars"></i>
    </div>

    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i>  Dashboard</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i>  Profile</a></li>
            <li><a href="logout_action.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></li>
        </ul>
    </div>

    <div class="sub-dashboard">
        <nav>
            <ul>
                <li><a href="sleep-tracker.php"> Main </a></li>
                <li><a href="sleep-history.php"> History</a></li>
            </ul>
        </nav>
    </div>
    
    <div class="container">
        <h2>Sleep Tracker</h2>

        <div>
            <label for="sleep-time">Sleep Time:</label>
            <input type="datetime-local" id="sleep-time" required>
        </div>
        <br>
        <br>
        <div>
            <label for="wake-time">Wake Time:</label>
            <input type="datetime-local" id="wake-time" required>
        </div>
        <br>
        <br>
        <div>
            <button onclick="saveSleepAndWakeTime(<?php echo $userId; ?>)">Save Sleep and Wake Time</button>
        </div>
    </div>


    <script>
       
       function saveSleepAndWakeTime(user) {
    var sleepTime = document.getElementById('sleep-time').value;
    var wakeTime = document.getElementById('wake-time').value;

    if (sleepTime && wakeTime) {
        var userId = user;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'sleep_tracker_action.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            var response = JSON.parse(xhr.responseText);
            alert(response.message);
        };

        xhr.send('user_id=' + userId + '&sleep_time=' + sleepTime + '&wake_time=' + wakeTime);
    } else {
        alert("Please enter both sleep time and wake time.");
    }
}

        function toggleNavbar() {
            var navbar = document.querySelector('.navbar');
            var currentLeft = window.getComputedStyle(navbar).left;
            if (currentLeft === '0px') {
                navbar.style.left = '-200px';
            } else {
                navbar.style.left = '0px';
            }
        }
    </script>
</body>
</html>

