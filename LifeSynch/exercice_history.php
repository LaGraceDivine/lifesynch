<?php
session_start();
include('dbConnect.php');

if (!isset($_SESSION['userId'])) {
    echo "User is not logged in.";
    exit();
}

$userId = mysqli_real_escape_string($conn, $_SESSION['userId']);

$query = "SELECT created_at, exercise_name, duration, calories FROM exercise_tracker WHERE user_id = '$userId' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    $exerciseHistory = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error executing query: " . mysqli_error($conn);
}

if (isset($_POST['created_at'])) {
    $created_at = mysqli_real_escape_string($conn, $_POST['created_at']);

    $query = "DELETE FROM exercise_tracker WHERE user_id = '$userId' AND created_at = '$created_at'";
    if (mysqli_query($conn, $query)) {
        header("Location: exercice_history.php?msg=RowDeleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise History</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }

        header {
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            color: #2eb11f;
        }

        .history-container {
            width: 80%;
            max-width: 900px;
            margin-top: 60px;
            margin-left: 90px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;

        }
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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

    <div class="history-container">
        <table>
        <h2>Exercise History</h2>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Exercise Type</th>
                    <th>Duration (mins)</th>
                    <th>Calories Burned</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exerciseHistory as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($entry['exercise_name']); ?></td>
                        <td><?php echo htmlspecialchars($entry['duration']); ?></td>
                        <td><?php echo htmlspecialchars($entry['calories']); ?></td>
                        <td>
                            <form method="POST" action="exercice_history.php" style="display:inline;">
                                <input type="hidden" name="created_at" value="<?php echo htmlspecialchars($entry['created_at']); ?>">
                                <button type="submit" class="delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
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
