<?php
include 'dbconfig.php';
session_start();

// Validate and sanitize user input
$name = mysqli_real_escape_string($conn, $_POST['name']);
$place = $_POST['place'];
$sale_date = mysqli_real_escape_string($conn, $_POST['sale_date']);
$type = $_POST['type']; // Cast to integer for safety
$des = $_POST['des'];
$poster = mysqli_real_escape_string($conn, $_POST['poster']);


$sql = "SELECT MAX(event_id) as `max` FROM event";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result)['max'];

if ($event == null) {
  $event = 1;
} else {
  $event += 1;
}

$id = $_SESSION["id"];

// Insert data into the event table using a prepared statement
$sql = "INSERT INTO event (event_id, name, type, place, sale_date, poster, description, org_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "issssssi", $event, $name, $type, $place, $sale_date, $poster, $des, $id);
if (mysqli_stmt_execute($stmt)) {
  // Event data inserted successfully
} else {
  echo "Error: " . mysqli_error($conn);
}

// Insert data into the round table
$datetime = json_decode($_POST['dthd']);
$sql = "SELECT MAX(round_id) as 'max' FROM round";
$num = mysqli_fetch_assoc(mysqli_query($conn, $sql))['max'];
if ($num == null) {
  $num = 1;
} else {
  $num += 1;
}
foreach ($datetime as $dt) {
  $sql = "INSERT INTO round (date_time, event_id) VALUES (?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "si", $dt, $event);

  if (mysqli_stmt_execute($stmt)) {
    // Round data inserted successfully
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>