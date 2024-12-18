<?php

include ('dbConnect.php');
header('Content-Type: application/json');

// Receive JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (isset($data['eventName'], $data['eventNotes'], $data['eventDate'])) {
    $eventName = $data['eventName'];
    $eventNotes = $data['eventNotes'];
    $eventDate = $data['eventDate'];

    // Database credentials
    $host = 'localhost';
    $dbname = 'calendar_db';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insert event into the database
        $stmt = $pdo->prepare("INSERT INTO events (event_name, event_notes, event_date) VALUES (:event_name, :event_notes, :event_date)");
        $stmt->bindParam(':event_name', $eventName);
        $stmt->bindParam(':event_notes', $eventNotes);
        $stmt->bindParam(':event_date', $eventDate);
        $stmt->execute();

        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Event saved']);
    } catch (PDOException $e) {
        // Return error response
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
