<?php
include 'dbconfig.php';

$zone = $_POST['zone'];
$price = $_POST['price'];
$n_rows = +$_POST['n_rows'];
$n_seat = +$_POST['n_seat'];
$price = $_POST['price'];
$seats_ID = json_decode($_POST['seats_ID']);
$seats_Col = json_decode($_POST['seats_Col']);
$round = +$_POST['round'];
$total = +$_POST['total'];
if ($n_seat != 0) {
  $total = count($seats_ID);
}
$sql = "SELECT MAX(event_id) as 'max' FROM event";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result)['max'];
$sql = "SELECT MAX(tkc_id) as 'max' FROM ticket_class";
$num = mysqli_fetch_assoc(mysqli_query($conn, $sql))['max'];

if ($num == null) {
  $num = 1;
} else {
  $num += 1;
}
$sql = "INSERT INTO ticket_class (tkc_id,tkc_name,event_id,price,width,capacity) VALUES ($num,'$zone',$event,$price,$n_seat,$total)";
$result = mysqli_query($conn, $sql);
$sql = "SELECT MAX(round_id) as 'max' FROM round";
$num = mysqli_fetch_assoc(mysqli_query($conn, $sql))['max'];

if ($n_seat == 0) {
  $sql = "SELECT tkc_id FROM ticket_class WHERE tkc_name = '$zone' and event_id = $event";
  $result2 = mysqli_fetch_assoc(mysqli_query($conn, $sql))['tkc_id'];
  for ($i = 1; $i < $round + 1; $i++) {
    $sql = "INSERT INTO ticket (seat_col,seat_num,tkc_id,round_id) VALUES ";
    $seat = 0;
    while ($seat < $total) {
      $seat++;
      $sql = $sql . " ('',$seat,'$result2','" . $num - $round + $i . "')";
      if ($seat != $total) {
        $sql = $sql . ",";
      }
    }
    $result = mysqli_query($conn, $sql);
  }
} else {
  $sql = "SELECT tkc_id FROM ticket_class WHERE tkc_name = '$zone' and event_id = $event";
  $result2 = mysqli_fetch_assoc(mysqli_query($conn, $sql))['tkc_id'];
  for ($i = 1; $i < $round + 1; $i++) {
    $sql = "INSERT INTO ticket (seat_col,seat_num,tkc_id,round_id) VALUES ";
    $seat = 0;
    while ($seat < count($seats_Col)) {
      $sql = $sql . " ('" . $seats_Col[$seat] . "','" . $seats_ID[$seat] . "','$result2','" . $num - $round + $i . "')";
      $seat++;
      if ($seat != count($seats_Col)) {
        $sql = $sql . ",";
      }
    }
    $result = mysqli_query($conn, $sql);
  }
}
mysqli_close($conn);

?>