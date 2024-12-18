<?php

include 'dbConnect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exercise_name = $_POST['exercise_name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $calories = $_POST['calories'];

    $start = DateTime::createFromFormat('H:i', $start_time);
    $end = DateTime::createFromFormat('H:i', $end_time);
    $duration = $start->diff($end);
    $duration_minutes = ($duration->h * 60) + $duration->i;

    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO exercise_tracker (user_id, exercise_name, duration, start_time, calories, completed, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, 1, ?, ?)";
    
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('isissss', $user_id, $exercise_name, $duration_minutes, $start_time, $calories, $created_at, $updated_at);
        
        if ($stmt->execute()) {
            header("Location: exercice-tracker.php");
        } else {
            echo "<script>alert('Error saving exercise data.'); window.location.href='exercice-tracker.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database connection error.'); window.location.href='exercice-tracker.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: rgba(0, 255, 0, 0.1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .form-group input {
            width: 80%;
            max-width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .form-group button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 80%;
            max-width: 300px;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .result {
            margin-top: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .live-time {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
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
                <li><a href="exercice-tracker.php"> Main </a></li>
                <li><a href="exercice_history.php"> History</a></li>
            </ul>
        </nav>
    </div>

    <div class="content-container">
        <div class="container">
            <h2>Exercise Tracker</h2>
            <div class="live-time" id="live-time"></div>
            
            <form id="exercise-form" action="exercice-tracker.php" method="POST">
                <div class="form-group">
                    <label for="exercise-name">Exercise Name:</label>
                    <input type="text" name="exercise_name" id="exercise-name" placeholder="Enter exercise name" required>
                </div>

                <div class="form-group">
                    <label for="start-time">Start Time:</label>
                    <input type="time" name="start_time" id="start-time" required>
                </div>

                <div class="form-group">
                    <label for="end-time">End Time:</label>
                    <input type="time" name="end_time" id="end-time" required>
                </div>

                <div class="form-group">
                    <label for="calories">Calories Burned:</label>
                    <input type="number" name="calories" id="calories" min="0" placeholder="e.g., 300" required>
                </div>

                <div class="form-group">
                    <button type="submit">Save Exercise Data</button>
                </div>
            </form>

            <div class="result" id="result"></div>
        </div>
    </div>

    <script>
        function updateLiveTime() {
            const now = new Date();
            document.getElementById("live-time").textContent = 
                `Current Time: ${now.toLocaleTimeString()}`;
        }
        setInterval(updateLiveTime, 1000);

        const hamburger = document.querySelector('.hamburger');
        const navbar = document.querySelector('.navbar');
        
        hamburger.addEventListener('click', () => {
            navbar.classList.toggle('open');
        });


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
