<?php
include('dbConnect.php');

if (isset($_POST['user_id']) && isset($_POST['sleep_time']) && isset($_POST['wake_time'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $sleep_time = mysqli_real_escape_string($conn, $_POST['sleep_time']);
    $wake_time = mysqli_real_escape_string($conn, $_POST['wake_time']);

    // Convert sleep_time and wake_time to DateTime objects
    $sleep_time_obj = new DateTime($sleep_time);
    $wake_time_obj = new DateTime($wake_time);

    // Calculate the difference (duration) between wake_time and sleep_time in minutes
    $duration = $sleep_time_obj->diff($wake_time_obj);

    // Get the duration in total minutes
    $duration_minutes = ($duration->h * 60) + $duration->i;

    // Optionally, store the duration in the database
    $query = "INSERT INTO sleep_data (user_id, sleep_time, wake_time, duration) 
              VALUES ('$user_id', '$sleep_time', '$wake_time', '$duration_minutes')";

    if (mysqli_query($conn, $query)) {
        $response = array("status" => "success", "message" => "Sleep and wake time saved successfully. Duration: $duration_minutes minutes.");
        echo json_encode($response);
    } else {
        $response = array("status" => "error", "message" => "Failed to save sleep and wake time.");
        echo json_encode($response);
    }
} else {
    $response = array("status" => "error", "message" => "Required fields are missing.");
    echo json_encode($response);
}

mysqli_close($conn);
?>
