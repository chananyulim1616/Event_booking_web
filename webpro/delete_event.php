<?php
session_start();
require_once("dbconfig.php");
$id = $_SESSION['id'];
$loc_e = $_POST['loc_e'];

$count = 0;
$sql = "SELECT event_id from event where org_id = $id ORDER BY event_id;";
    $result = mysqli_query($conn, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $count += 1;
       if($count == $loc_e){
        $event_id = $rows['event_id'];
       }
    }

$sql = "SELECT sale_date from event where event_id = $event_id";
$result = strtotime(mysqli_fetch_assoc(mysqli_query($conn, $sql))['sale_date']);
    if(time() < $result){
    $sql = "SELECT tkc_id from ticket_class where event_id = $event_id;";
    $result = mysqli_query($conn, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $sql2 = "DELETE FROM invoice WHERE tkc_id = ".$rows['tkc_id'];
        $result2 = mysqli_query($conn, $sql2);
        $sql2 = "DELETE FROM ticket WHERE tkc_id = ".$rows['tkc_id'];
        $result2 = mysqli_query($conn, $sql2);
    }
    $sql2 = "DELETE FROM round WHERE event_id = $event_id";
    $result2 = mysqli_query($conn, $sql2);

    $sql2 = "DELETE FROM ticket_class WHERE event_id = $event_id";
    $result2 = mysqli_query($conn, $sql2);

    $sql2 = "DELETE FROM event WHERE event_id = $event_id";
    $result2 = mysqli_query($conn, $sql2);
    echo 'success';
}?>