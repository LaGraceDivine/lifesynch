<?php
include 'dbConnect.php';
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];  
$date = date('Y-m-d');

$sql = "SELECT SUM(amount) as currentIntake FROM water_intake WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $date);
$stmt->execute();
$stmt->bind_result($currentIntake);
$stmt->fetch();

echo json_encode(['currentIntake' => $currentIntake ? $currentIntake : 0]);

$stmt->close();
$conn->close();
?>
