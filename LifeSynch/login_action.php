<?php
session_start();
include 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM users WHERE users.email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $hashedPasswordFromDatabase = $row['password'];
            if (password_verify($password, $hashedPasswordFromDatabase)) {
                $_SESSION['userId'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                echo json_encode(['success' => true, 'message' => 'Login successful']);
                exit();

            } else {
                
                echo json_encode(['success' => false, 'message' => 'Incorrect password']);
            }
        } else {
          
            echo json_encode(['success' => false, 'message' => 'Email not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
