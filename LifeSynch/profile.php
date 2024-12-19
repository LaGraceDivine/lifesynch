<?php
session_start();
include 'dbConnect.php';

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['userId'];
$message = "";

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
var_dump($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(["success" => false, "message" => "Invalid email format."]);
      exit();
  }
  
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
  $stmt->bind_param("si", $email, $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
      echo json_encode(["success" => false, "message" => "Email is already taken."]);
      exit();
  }

  $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
  $stmt->bind_param("ssi", $username, $email, $userId);
  
  if ($stmt->execute()) {
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      echo json_encode(["success" => true, "message" => "Profile updated successfully!"]);
  } else {
      echo json_encode(["success" => false, "message" => "Error updating profile."]);
  }
  $stmt->close();
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    if (password_verify($currentPassword, $user['password'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Password changed successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error changing password."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_picture'])) {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $uploadFile, $userId);

                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Profile picture updated successfully!',
                        'profile_picture' => $uploadFile
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error updating profile picture.'
                    ]);
                }
                $stmt->close();
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error uploading file.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid image file type.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No file uploaded or upload error.'
        ]);
    }
    exit();
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
    <title>Profile Management</title>
    <style>
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }

      body {
          font-family: 'Arial', sans-serif;
          background-color: #f4f4f9;
          color: #333;
          line-height: 1.6;
      }

      .container {
          width: 80%;
          max-width: 1000px;
          margin: 50px auto;
          padding: 20px;
          background-color: white;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      h1 {
          text-align: center;
          color: #333;
          font-size: 2.5rem;
          margin-bottom: 30px;
      }

      .profile-picture {
          text-align: center;
          margin-bottom: 30px;
      }

      .profile-picture img {
          width: 150px;
          height: 150px;
          border-radius: 50%;
          object-fit: cover;
          border: 3px solid #ddd;
          margin-bottom: 20px;
      }

      .profile-picture button {
          padding: 8px 16px;
          background-color: #4CAF50;
          color: white;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          font-size: 1rem;
          transition: background-color 0.3s;
      }

      .profile-picture button:hover {
          background-color: #45a049;
      }

      form {
          display: flex;
          flex-direction: column;
          margin-bottom: 20px;
      }

      form label {
          font-size: 1rem;
          margin-bottom: 5px;
          color: #333;
      }

      form input {
          padding: 10px;
          font-size: 1rem;
          border: 1px solid #ccc;
          border-radius: 4px;
          margin-bottom: 15px;
          outline: none;
          transition: border-color 0.3s;
      }

      form input:focus {
          border-color: #4CAF50;
      }

      form button {
          padding: 10px 20px;
          background-color: #4CAF50;
          color: white;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          font-size: 1rem;
          transition: background-color 0.3s;
      }

      form button:hover {
          background-color: #45a049;
      }

      #message {
          text-align: center;
          margin-top: 20px;
          font-size: 1.2rem;
      }

      #message p {
          padding: 10px;
          border-radius: 4px;
      }

      #message p.success {
          background-color: #d4edda;
          color: #155724;
      }

      #message p.error {
          background-color: #f8d7da;
          color: #721c24;
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
        .history-container {
            width: 80%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

      @media (max-width: 768px) {
          .container {
              width: 95%;
              padding: 15px;
          }

          h1 {
              font-size: 2rem;
          }

          .profile-picture img {
              width: 120px;
              height: 120px;
          }

          form button {
              font-size: 0.9rem;
          }
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

    <div class="navbar">
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i>  Dashboard</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i>  Profile</a></li>
            <li><a href="logout_action.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h1>Profile Management</h1>

        <div id="message"></div>

        <div class="profile-picture">
            <img id="profileImg" src="<?= htmlspecialchars($user['profile_picture'] ?? 'default.png') ?>" alt="Profile Picture">
        </div>

        <form id="uploadForm" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" required>
            <button type="submit" name="upload_picture">Upload Picture</button>
        </form>

        <form id="profileForm">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <form id="passwordForm">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData();
            formData.append('profile_picture', document.getElementById('profile_picture').files[0]);
            formData.append('upload_picture', true);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'profile.php', true);

            xhr.onload = function() {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('profileImg').src = response.profile_picture;
                    document.getElementById('message').innerHTML = "<p style='color: green;'>Profile picture updated successfully!</p>";
                } else {
                    document.getElementById('message').innerHTML = "<p style='color: red;'>" + response.message + "</p>";
                }
            };

            xhr.send(formData);
        });

        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'profile.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('message').innerHTML = "<p style='color: green;'>Profile updated successfully!</p>";
                } else {
                    document.getElementById('message').innerHTML = "<p style='color: red;'>" + response.message + "</p>";
                }
            };
            xhr.send('username=' + username + '&email=' + email + '&update_profile=true');
        });

        document.getElementById('passwordForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var currentPassword = document.getElementById('current_password').value;
            var newPassword = document.getElementById('new_password').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'profile.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById('message').innerHTML = "<p style='color: green;'>Password changed successfully!</p>";
                } else {
                    document.getElementById('message').innerHTML = "<p style='color: red;'>" + response.message + "</p>";
                }
            };
            xhr.send('current_password=' + currentPassword + '&new_password=' + newPassword + '&change_password=true');
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
