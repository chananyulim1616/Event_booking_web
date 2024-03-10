
<?php
require_once("dbconfig.php");
session_start();

    $zone_select = $_POST["zone_select"];
    $seat_id = json_decode($_POST["seat_id"]);
    $eventID = $_POST['event_id'];
    $round = $_POST['round'];
    $sql = "SELECT COUNT(ticket_id) as max ,tkc_id  FROM ticket JOIN ticket_class using(tkc_id) where invoice_id IS NULL and round_id = $round and tkc_name = '$zone_select' and event_id = $eventID";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $max = $result['max'];
    if ($max >= 6) {
        $max = 6;
    }
    if (count($seat_id) > $max) {
        header("Location: index.php");
    }
    $tkcid = $result['tkc_id'];
    $sql = "SELECT *, CONCAT(seat_col,seat_num) as full FROM ticket JOIN ticket_class USING (tkc_id) WHERE event_id = $eventID and round_id = $round and tkc_name ='$zone_select' and invoice_id IS NULL and ticket_id IN(";
    $count = 0;
    while ($count < count($seat_id)) {
        if ($count == 0) {
            $where = $seat_id[$count];
        } else {
            $where = "$where ," . $seat_id[$count];
        }
        $count++;
    }
    $sql = $sql . $where . ")";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == count($seat_id)) {
        $pk = bin2hex(random_bytes(16));
        $sql = "UPDATE ticket SET invoice_id='$pk' WHERE ticket_id IN(";
        $result = mysqli_query($conn, $sql.$where. ")");
        $sql = "SELECT sum(price) FROM ticket JOIN ticket_class USING (tkc_id) WHERE event_id = $eventID and tkc_name = '$zone_select' and ticket_id IN(";
        $result = mysqli_query($conn, $sql . $where . ")");
        $price = mysqli_fetch_assoc($result)['sum(price)'];
        $sql = "INSERT INTO invoice (invoice_id,total_price,user_id,status,tkc_id,total_tickets) VALUES ('$pk',$price,'".$_SESSION["id"]."','สำเร็จ',$tkcid,".count($seat_id).")";

        $result = mysqli_query($conn, $sql);
    }
    else{
        echo 'already';
    }
    echo 'success' 

?>