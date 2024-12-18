<?php
session_start();

// Include database connection file
$included = @include 'dbConnect.php';
if (!$included) {
    die('Error: Could not include dbConnect.php. Ensure the file exists and the path is correct.');
}

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the incoming JSON data from the request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if the required fields are present
if (isset($inputData['name']) && isset($inputData['email']) && isset($inputData['question'])) {
    $name = $conn->real_escape_string($inputData['name']);
    $email = $conn->real_escape_string($inputData['email']);
    $question = $conn->real_escape_string($inputData['question']);

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, question) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $question);

    if ($stmt->execute()) {
        // Return a success message in JSON format
        $response = ['success' => true, 'message' => 'Your message has been sent. We will get back to you!'];
    } else {
        // Return an error message if the database operation fails
        $response = ['success' => false, 'message' => 'Failed to send message. Please try again later.'];
    }

    $stmt->close();
} else {
    // Return an error message if the required fields are missing
    $response = ['success' => false, 'message' => 'Please fill out all fields.'];
}

$conn->close();

// Send the response back to the client in JSON format
header('Content-Type: application/json');
echo json_encode($response);
?>
