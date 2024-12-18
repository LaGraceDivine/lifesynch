<?php

include 'dbConnect.php';
session_start();  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['userId'])) {
        echo "User not logged in.";
        exit();
    }

    $userId = $_SESSION['userId'];  
    $amount = $_POST['amount'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO water_intake (user_id, date, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $userId, $date, $amount);
    
    if ($stmt->execute()) {
        echo "Water intake added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    
    header("Location: hydration-tracker.php");
    exit();
}
?>
