<?php
// Start session for user interaction
session_start();

// Include database connection
include 'dbConnect.php';

// Initialize response array
$response = array('success' => false, 'message' => 'An unexpected error occurred.');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    // Sanitize and trim user input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $timezone = 'gh'; // Default timezone

    // Validate input fields
    if (empty($username) || empty($email) || empty($password)) {
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    // Check if the email already exists in the database
    $emailCheckQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($emailCheckQuery);

    if ($stmt === false) {
        $response['message'] = "Database error: " . $conn->error;
        echo json_encode($response);
        exit();
    }

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

    // Hash the password securely
    if (empty($password)) {
        $response['message'] = "Password cannot be empty.";
        echo json_encode($response);
        exit();
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Get current date and time for registration
    $createdAt = $updatedAt = date("Y-m-d H:i:s");

    // Insert new user into the database
    $sql = "INSERT INTO users (username, email, password, timezone, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $response['message'] = "SQL prepare error: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $timezone, $createdAt, $updatedAt);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Registration successful! Please log in.";
    } else {
        // Log error and inform user of failure
        $response['message'] = "An error occurred while processing your registration. Please try again later.";
        error_log("Insert Execute Error: " . $stmt->error);
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle invalid request
    $response['message'] = "Invalid request. Please make sure all fields are filled out.";
}

// Output the response as JSON
echo json_encode($response);
?>
