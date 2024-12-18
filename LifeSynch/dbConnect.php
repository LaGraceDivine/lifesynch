<?php
  $db_host = 'localhost';
  $db_user = 'igirubuntu';
  $db_password = 'igirubuntu';
  $db_db = 'LifeSynch';
  $db_port = 3306;

  $conn = new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db,
	$db_port
  );
	
  if ($conn->connect_error) {
    echo 'Errno: '.$conn->connect_errno;
    echo '<br>';
    echo 'Error: '.$conn->connect_error;
    exit();
  }
?>