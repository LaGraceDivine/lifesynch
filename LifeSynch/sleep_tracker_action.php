<?php
include('dbConnect.php');

if (isset($_POST['user_id']) && isset($_POST['sleep_time']) && isset($_POST['wake_time'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $sleep_time = mysqli_real_escape_string($conn, $_POST['sleep_time']);
    $wake_time = mysqli_real_escape_string($conn, $_POST['wake_time']);

    $query = "INSERT INTO sleep_data (user_id, sleep_time, wake_time) 
              VALUES ('$user_id', '$sleep_time', '$wake_time')";

    if (mysqli_query($conn, $query)) {
        $response = array("status" => "success", "message" => "Sleep and wake time saved successfully.");
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
