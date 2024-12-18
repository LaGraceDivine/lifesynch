<?php

session_start();


include 'dbConnect.php';

$response = array('success' => false, 'message' => 'An unexpected error occurred.');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $timezone = 'gh';

  
    $emailCheckQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        
        $response['message'] = "An account with this email already exists. Please try logging in or use a different email.";
        $stmt->close();
        $conn->close();
        echo json_encode($response);
        exit();
    }
    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $createdAt = $updatedAt = date("Y-m-d H:i:s");

    $sql = "INSERT INTO users (username, email, password, timezone, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $timezone, $createdAt, $updatedAt);

    if ($stmt->execute()) {
        
        $response['success'] = true;
        $response['message'] = "Registration successful! Please log in.";
    } else {
       
        $response['message'] = "An error occurred while processing your registration. Please try again later.";
        error_log("Insert Execute Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
  
    $response['message'] = "Invalid request. Please make sure all fields are filled out.";
}

echo json_encode($response);
?>
