<?php
include 'dbConnect';

function isAuthorized() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAuthorized()) {
        echo "Unauthorized action.";
        exit;
    }

    if (isset($_POST['create'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);

        $roleCheckStmt = $db->prepare("SELECT COUNT(*) FROM Roles WHERE RoleID = ?");
        $roleCheckStmt->execute([$role_id]);
        if ($roleCheckStmt->fetchColumn() == 0) {
            echo "Invalid RoleID.";
            exit;
        }

        if ($username && $email && $password && $role_id) {
            $stmt = $db->prepare("INSERT INTO users (Username, Email, PasswordHash, RoleID, IsApproved) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $username,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $role_id,
                1234
            ]);
            echo "User created successfully.";
        } else {
            echo "Invalid input for creating a user.";
        }
    }

    if (isset($_POST['update'])) {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);

        $roleCheckStmt = $db->prepare("SELECT COUNT(*) FROM Roles WHERE RoleID = ?");
        $roleCheckStmt->execute([$role_id]);
        if ($roleCheckStmt->fetchColumn() == 0) {
            echo "Invalid RoleID.";
            exit;
        }

        if ($user_id && $username && $email && $role_id) {
            $stmt = $db->prepare("UPDATE users SET Username = ?, Email = ?, RoleID = ? WHERE UserID = ?");
            $stmt->execute([
                $username,
                $email,
                $role_id,
                $user_id
            ]);
            echo "User updated successfully.";
        } else {
            echo "Invalid input for updating a user.";
        }
    }

    if (isset($_POST['delete'])) {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        if ($user_id) {
            $stmt = $db->prepare("DELETE FROM users WHERE UserID = ?");
            $stmt->execute([$user_id]);
            echo "User deleted successfully.";
        } else {
            echo "Invalid input for deleting a user.";
        }
    }

    if (isset($_POST['approve'])) {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        if ($user_id) {
            $stmt = $db->prepare("UPDATE users SET IsApproved = 1 WHERE UserID = ?");
            $stmt->execute([$user_id]);
            echo "User approved successfully.";
        } else {
            echo "Invalid input for approving a user.";
        }
    }
}

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$limit = 100;
$offset = ($page - 1) * $limit;
$stmt = $db->prepare("
    SELECT u.UserID, u.Username, u.Email, r.RoleName, u.IsApproved 
    FROM users u 
    JOIN Roles r ON u.RoleID = r.RoleID 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->query("SELECT * FROM messages LIMIT 50");
$chatbot_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-container {
            margin: 20px 0;
        }
        .form-container input, .form-container select, .form-container button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
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

        #event-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #event-form {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        #event-form input,
        #event-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        #event-form button {
            background-color: #3a7dff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #event-form button:hover {
            background-color: #4CAF50;
        }

        @media (max-width: 768px) {
            .navbar {
                left: -100%;
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

            .navbar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            cursor: pointer;
            font-size: 30px;
            z-index: 1001;
        }
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
                <li><a href="#users"> Users Information </a></li>
                <li><a href="#create"> Create a User </a></li>
                <li><a href="#edit"> Edit User Information </a></li>
                <li><a href="#chatbot"> Chatbot Data</a></li>
            </ul>
        </nav>
    </div>

    <h1>Admin Dashboard</h1>

    <section id="users">
        <h2>List of Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Approved</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['UserID']) ?></td>
                <td><?= htmlspecialchars($user['Username']) ?></td>
                <td><?= htmlspecialchars($user['Email']) ?></td>
                <td><?= htmlspecialchars($user['RoleID']) ?></td>
                <td><?= htmlspecialchars($user['IsApproved'] ? 'Yes' : 'No') ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['UserID']) ?>">
                        <button type="submit" name="approve">Approve</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['UserID']) ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- Create User Section -->
    <section id="create">
        <h2>Create User</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role_id">
                <option value="1">User</option>
                <option value="2">Admin</option>
                <option value="3">User Administrator</option>
            </select>
            <button type="submit" name="create">Create User</button>
        </form>
    </section>

    <section id="edit">
        <h2>Edit User</h2>
        <form method="post">
            <input type="hidden" name="user_id" placeholder="User ID" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role_id">
                <option value="1">User</option>
                <option value="2">Admin</option>
            </select>
            <button type="submit" name="update">Update User</button>
        </form>
    </section>

    <section id="chatbot">
        <h2>Chatbot Data</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Question</th>
            </tr>
            <?php foreach ($chatbot_data as $data): ?>
            <tr>
                <td><?= htmlspecialchars($data['username']) ?></td>
                <td><?= htmlspecialchars($data['email']) ?></td>
                <td><?= htmlspecialchars($data['question']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

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
