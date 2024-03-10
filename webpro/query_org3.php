<?php
include 'dbconfig.php';
session_start();
// Check if all required POST values are set

    // Validate and sanitize user input
    $name = $_POST['name'];
    $place = $_POST['place'];
    $sale_date = $_POST['sale_date'];
    $type = $_POST['type'];
    $des = $_POST['des'];
    $poster = $_POST['poster'];
    $event_id = $_POST['eventid'];

    $sql = "UPDATE event SET name = ?, place = ?, sale_date = ?, type = ?, description = ?, poster = ? WHERE event_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $place, $sale_date, $type, $des, $poster, $event_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Event data updated successfully
        echo "Event updated successfully.";
    } else {
        // Handle the case where the update fails
        echo "Error updating event: " . mysqli_error($conn); // Display an error message for debugging
    }

    // Close the statement
    mysqli_stmt_close($stmt);


// Close the database connection
mysqli_close($conn);
?>