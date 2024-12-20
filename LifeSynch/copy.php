<?php
    // Start the session
    session_start();

    try {
        // Connect to the SQLite database
        $db = new PDO('sqlite:database.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Handle the form submissions for creating, updating, deleting, and approving users
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);

                if ($username && $email && $password && $role_id) {
                    $stmt = $db->prepare("INSERT INTO users (Username, Email, PasswordHash, RoleID, IsApproved) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $username,
                        $email,
                        password_hash($password, PASSWORD_DEFAULT),
                        $role_id,
                        0 // Default IsApproved as 0
                    ]);
                }
            }

            if (isset($_POST['update'])) {
                $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);

                if ($user_id && $username && $email && $role_id) {
                    $stmt = $db->prepare("UPDATE users SET Username = ?, Email = ?, RoleID = ? WHERE UserID = ?");
                    $stmt->execute([
                        $username,
                        $email,
                        $role_id,
                        $user_id
                    ]);
                }
            }

            if (isset($_POST['delete'])) {
                $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

                if ($user_id) {
                    $stmt = $db->prepare("DELETE FROM users WHERE UserID = ?");
                    $stmt->execute([$user_id]);
                }
            }

            if (isset($_POST['approve'])) {
                $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

                if ($user_id) {
                    $stmt = $db->prepare("UPDATE users SET IsApproved = 1 WHERE UserID = ?");
                    $stmt->execute([$user_id]);
                }
            }
        }

        // Fetch all users
        $users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

        // Fetch chatbot data (example table)
        $chatbot_data = $db->query("SELECT * FROM messages")->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
    ?>

    <!-- List of Users Section -->
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

    <!-- Edit User Section -->
    <section id="edit">
        <h2>Edit User</h2>
        <form method="post">
            <input type="hidden" name="user_id" placeholder="User ID" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role_id">
                <option value="1">User</option>
                <option value="2">Admin</option>
                <option value="3">User Administrator</option>
            </select>
            <button type="submit" name="update">Update User</button>
        </form>
    </section>

    <!-- Chatbot Data Section -->
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