<?php

include ('dbConnect.php');

header('Content-Type: application/json');

// Database credentials
$host = 'localhost';
$dbname = 'LifeSync';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all events from the database
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return events in JSON format
    echo json_encode(['status' => 'success', 'events' => $events]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
