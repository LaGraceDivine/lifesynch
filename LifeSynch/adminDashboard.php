<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <a href="#users">List of Users</a>
        <a href="#create">Create User</a>
        <a href="#edit">Edit User</a>
        <a href="#chatbot">Chatbot Data</a>
    </nav>

    <h1>Admin Dashboard</h1>

    <?php include('process.php'); ?>

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

    <script src="script.js"></script>

</body>
</html>
