<?php
session_start();

$included = @include 'dbConnect.php';
if (!$included) {
    die('Error: Could not include dbConnect.php. Ensure the file exists and the path is correct.');
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

$motivational_query = "SELECT content FROM motivational_messages ORDER BY RAND() LIMIT 1";
$result = $conn->query($motivational_query);

if ($result && $result->num_rows > 0) {
    $api_link = $result->fetch_assoc()['content'];

    $api_content = fetchApiContent($api_link);

    $data = json_decode($api_content, true);
    if (isset($data['text'], $data['verse'])) {
        $text = $data['text'];
        $reference = $data['verse'];

        $motivational_message = "\"$text\" $reference";
    } else {
        $motivational_message = "Stay motivated and achieve your goals!";
    }
} else {
    $motivational_message = "Stay motivated and achieve your goals!";
}

$conn->close();

function fetchApiContent($url) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $response = false;
    }

    curl_close($curl);

    return $response;
}

$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'admin';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeSynch Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgba(0, 255, 0, 0.1); 
            ;
        }


        .navbar {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .navbar li {
            margin: 0 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
        }

        .navbar a:hover {
            background-color: #555;
            border-radius: 4px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: url('background-image.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 100px 20px;
            height: 20vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero h1,
        .hero p {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 2.5em;
            margin: 0;
        }

        .hero p {
            margin: 10px 0 0;
            font-size: 1.2em;
        }


        /* Boxes Section */
        .boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px auto;
            padding: 20px;
            max-width: 1200px;
        }

        .box {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
            text-align: center;
        }

        .box img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .box:hover {
            transform: translateY(-5px);
        }

        .box h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="icon-home"></i>Dashboard</a></li>
            <li><a href="profile.php"><i class="icon-profile"></i>Profile</a></li>
            <?php if ($user_role == 'admin'): ?>
            <li><a href="adminDashboard.php"><i class="icon-profile"></i>Admin Dashboard</a></li><?php endif; ?>
            <li><a href="logout_action.php"><i class="icon-logout"></i>Logout</a></li>
        </ul>
    </div>

    <div class="hero">
        <h1>Welcome Back, <?php echo htmlspecialchars($username); ?>!</h1>
        <p id="currentDate"></p>
    </div>

    <div style="text-align: center; padding: 20px;">
        <p><h3><em><?php echo htmlspecialchars($motivational_message); ?></em></h3></p>
    </div>

    <div class="boxes">
        <div class="box" onclick="location.href='hydration-tracker.php'">
            <img src="water-tracker.jpg" alt="Water Tracking">
            <h3>Hydration Tracker</h3>
        </div>
        <div class="box" onclick="location.href='sleep-tracker.php'">
            <img src="sleep-tracker.jpg" alt="Sleep Tracking">
            <h3>Sleep Tracker</h3>
        </div>
        <div class="box" onclick="location.href='exercice-tracker.php'">
            <img src="exercice-tracker.jpg" alt="Exercise Tracking">
            <h3>Exercise Tracker</h3>
        </div>
        <div class="box" onclick="location.href='calendar.php'">
            <img src="calendar.jpg" alt="Calendar">
            <h3>My Calendar</h3>
        </div>
    </div>

    <footer>
        &copy; 2024 LifeSynch. All rights reserved.
    </footer>

    <script>
        const currentDate = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('currentDate').textContent = `Today, we are on ${currentDate.toLocaleDateString('en-US', options)}`;
    </script>
</body>
</html>
