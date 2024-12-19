<?php

include 'dbConnect.php';
session_start();
var_dump($_SESSION);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reminder_time'])) 
{
    
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;

    if ($userId) 
    {
        $reminderTime = $_POST['reminder_time'];
        $stmt = $conn->prepare("INSERT INTO water_reminders (user_id, reminder_time) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $reminderTime);
        $stmt->execute();
        $stmt->close();
        header("Location: water-reminder.php");
        exit();

    } 
    else {
        header("Location: hydration-tracker.php");
        exit();
    }
}

$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
$query = "SELECT reminder_time FROM water_reminders WHERE user_id = '$userId'";
$result = $conn->query($query);
$reminders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row['reminder_time'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['time'])) 
{
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
    $reminderTime = $_GET['time'];
    echo "Reminder Time: " . $reminderTime;
    echo "User ID: " . $userId;

    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($userId && $reminderTime) 
    {
        echo "Valid userId and remainder time\n";
        $stmt = $conn->prepare("DELETE FROM water_reminders WHERE user_id = ? AND reminder_time = ?");
        echo $conn->error;
        if ($stmt === false) 
        {
            echo "Statement is false";
            die('MySQL prepare error: ' . $conn->error);
        }
        else
        {
            echo "Statement is true";
        }
        echo "Statement prepared\n";
        $stmt->bind_param("is", $userId, $reminderTime);
        $stmt->execute();
        $stmt->close();
        echo "Done reminding";
        header("Location: water-reminder.php");
        exit();
    } else {
        header("Location: hydration-tracker.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Water Reminder</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 60px;
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
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        input[type="number"] {
            padding: 10px;
            width: 100%;
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

        .intake-summary {
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

            .form-container {
                max-width: 90%;
                margin-top: 20px;
            }
        }

        .reminder-container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            border-radius: 8px;
            margin-top: 80px;
            margin-left: 400px;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="time"] {
            padding: 8px;
            width: 80%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 80%;
        }

        button:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 5px 0;
            padding: 8px;
            border-radius: 5px;
        }
        .delete-btn {
            color: #45a049;
            text-decoration: none;
            font-size: 14px;
            padding-left: 10px;
        }

    </style>
</head>
<body>

<div class="navbar-toggle" onclick="toggleNavbar()">
    <i class="fas fa-bars"></i>
</div>

<div class="navbar">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="logout_action.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="sub-dashboard">
    <nav>
        <ul>
            <li><a href="hydration-tracker.php">Main</a></li>
            <li><a href="water-reminder.php">Hydration Reminder</a></li>
            <li><a href="hydration-history.php">History</a></li>
        </ul>
    </nav>
</div>

<div class="reminder-container">
    <h2>Set Water Reminders</h2>
    <form action="water-reminder.php" method="POST">
        <div>
            <label for="reminder-time">Set Reminder Time:</label>
            <input type="time" id="reminder-time" name="reminder_time" required>
            <button type="submit">Save Reminder</button>
        </div>
    </form>   

    <div>
        <h3>Saved Reminders</h3>
        <ul id="reminder-list">
            <?php
            // Loop through the reminders and display each one
            foreach ($reminders as $reminder) {
                echo "<li>" . htmlspecialchars($reminder) . " 
                    <form action='water-reminder.php' method='GET' style='display:inline;'>
                        <input type='hidden' name='time' value='" . htmlspecialchars($reminder) . "'>
                        <button type='submit' onclick='return confirm(\"Are you sure you drank water? If yes, click OK. If no, please drink first or click Cancel.\")'>Done</button>
                    </form>
                </li>";
            }
            ?>
        </ul>
    </div>
</div>
<script>

    const reminders = <?php echo json_encode($reminders); ?>;

    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    function playReminderSound() {
        const message = "It's time to drink water.";
        const utterance = new SpeechSynthesisUtterance(message);

        utterance.rate = 1;
        utterance.pitch = 1;
        utterance.volume = 1;

        let repeatCount = 0;

        utterance.onend = () => {
            repeatCount++;
            if (repeatCount < 5) {
                window.speechSynthesis.speak(utterance);
            }
        };

        window.speechSynthesis.speak(utterance);
    }

    function sendNotification() {
        const title = "Water Reminder";
        const options = {
            body: "It's time to drink water!",
            icon: "water-icon.png",
            requireInteraction: true,
        };

        if (Notification.permission === "granted") {
            const notification = new Notification(title, options);

            notification.onclick = () => {
                playReminderSound();
                window.focus();
            };
        } else {
            console.error("Notification permissions denied.");
        }
    }

    function checkReminders() {
        const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const reminders = <?php echo json_encode($reminders); ?>;

        if (reminders.includes(currentTime)) {
            playReminder();
        }
    }
    setInterval(checkReminders, 20000);

        if (Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            if (permission !== 'granted') {
                alert("Please allow notifications for water reminders to work!");
            }
        });
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
