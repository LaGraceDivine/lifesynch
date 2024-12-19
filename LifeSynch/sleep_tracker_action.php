<?php
include('dbConnect.php');

if (isset($_POST['user_id']) && isset($_POST['sleep_time'])) {
    $user_id = $_POST['user_id'];
    $sleep_time = $_POST['sleep_time'];
    $sleep_time = str_replace("T", " ", $sleep_time) . ":00";
    $created_at = date('Y-m-d H:i:s');
    $query = "INSERT INTO sleep_tracker (user_id, sleep_time, wake_time, created_at) 
              VALUES (?, ?, ?,?)";
    if ($stmt = mysqli_prepare($conn, $query)) {
        
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $sleep_time, $created_at);

        if (mysqli_stmt_execute($stmt)) {
          
            echo json_encode(['status' => 'success', 'message' => 'Sleep time recorded']);
        } else {
        
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the query']);
    }
} else {
    
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
}


?>


