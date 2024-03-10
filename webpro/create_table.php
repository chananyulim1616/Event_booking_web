<?php
require_once("dbconfig.php");
session_start();

$eid = $_POST['e_id'];
$id = $_SESSION['id'];
$sql = "SELECT tkc_name,price,width FROM ticket_class WHERE event_id = $eid ORDER BY price";
$result = mysqli_query($conn, $sql);
$zones = array();
while ($rows = mysqli_fetch_assoc($result)) {
    $zones[$rows['tkc_name']] = array($rows['price'], $rows['width']);
}
$sql = "SELECT round_id,date_time FROM round WHERE event_id = $eid ";
$result = mysqli_query($conn, $sql);
$rounds = array();
while ($rows = mysqli_fetch_assoc($result)) {
    $rounds[$rows['round_id']] = $rows['date_time'];
}
    $zone_select = $_POST["zoneSelect"];
    $round_select = $_POST["roundSelect"];

$sql = "SELECT tkc_name,COUNT(DISTINCT(seat_col)) as zone_num FROM ticket join ticket_class using (tkc_id) where event_id = $eid  group by tkc_id order by price ;";
$result = mysqli_query($conn, $sql);
$zone_num = array();
while ($rows = mysqli_fetch_assoc($result)) {
    $zone_num[$rows['tkc_name']] = $rows['zone_num'];
}
$sql = "SELECT tkc_name, ticket_id, seat_num, invoice_id, seat_col FROM ticket join ticket_class using (tkc_id) where
 event_id = $eid and tkc_name = '$zone_select' and round_id=$round_select order by price ;";
$result = mysqli_query($conn, $sql);
$seats = array();
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($seats, array($rows['tkc_name'], $rows['ticket_id'], $rows['seat_num'], $rows['invoice_id'], $rows['seat_col']));
}
$sql = "SELECT DISTINCT(seat_col) as col_name FROM ticket join ticket_class using (tkc_id) where tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$col_name = array();
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($col_name, $rows['col_name']);
}
$sql = "SELECT capacity FROM ticket_class where tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$capacity = mysqli_fetch_assoc($result)['capacity'];
$sql = "SELECT ticket_id, date_time, COALESCE(CONCAT(fname,' ',lname), '-')  as fullname FROM
ticket t
LEFT JOIN ticket_class tkc ON t.tkc_id = tkc.tkc_id
JOIN round r USING (round_id)
LEFT JOIN invoice i ON t.invoice_id = i.invoice_id
LEFT JOIN customer c ON i.user_id = c.user_id
WHERE tkc.event_id = $eid and date_time = '". $rounds[$round_select] ."' and tkc_name = '".$zone_select."' ORDER BY ticket_id DESC; ";
$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>
            <?php

            if ($zones[$zone_select][1] != 0): ?>
                <div class="my-12 min-h-[24rem]" id='result'>
                <div class="movie-container">
                        <label for="">การจอง</label>
                        <div class="flex pb-2 pt-3 border-2 border-solid rounded-lg justify-around">
                            <div class="flex flex-col justify-center items-center">
                                <div class="seat"></div>
                                <p>ที่นั่งที่ว่าง</p>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <div class="seat selected"></div>
                                <p>ที่นั่งที่ถูกจอง</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center gap-4 w-full overflow-x-auto overflow-y-hidden" id='seat-container'>
                        <?php
                        echo '<div class="row2 left">
            <div class="screen3"></div>
            <p class="text-base">ชื่อ</p>';
                        for ($rows = 0; $rows < $zone_num[$zone_select]; $rows++) {
                            echo "<div class='flex gap-2  items-center' id='row3'><p class='h-6 border-solid border-2 rounded-lg text-base w-8 text-center'>" . $col_name[$rows] . "</p></div>";
                        }
                        echo '</div><div class="row2"><div class="container2"><div class="screen"></div></div>';
                        $count = 0;
                        $name_row = $seats[$count][1];
                        for ($rows = 0; $rows < $zone_num[$zone_select]; $rows++) {
                            echo "<div class='rowcss' id='rowname'>";
                            $name_row = $seats[$count][4];
                            for ($columns = 1; $columns <= $zones[$zone_select][1]; $columns++) {
                                if ($columns == $seats[$count][2] and $name_row == $seats[$count][4]) {
                                    if ($seats[$count][3] != '') {
                                        echo "<div class='seat selected'";
                                    } else {
                                        echo "<div class='seat'";
                                    }
                                    echo "id= '" . $seats[$count][1] . "'onclick='check(id)' '></div>";
                                    $count++;
                                    if ($count >= $capacity) {
                                        break 2;
                                    }
                                } else {
                                    echo "<div class='noseat'></div>";
                                }
                            }

                            echo "</div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
    <?php if ($zones[$zone_select][1] == 0): ?>
        <div id='result' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
        <table id="example" class="stripe hover text-center" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                <thead >
                    <tr>
                        <th data-priority="1">ลำดับบัตรเข้าชม</th>
                        <th data-priority="2">ชื่อ-นามสกุล</th>
                        <th data-priority="3">ประเภทบัตร</th>
                        <th data-priority="4">รอบการแสดง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    function dateTimeToWords($dateString)
                    {
                        $date = new DateTime($dateString);
                        return $date->format('j F  Y, เวลา g:i A');
                    }
                    $count = 1;
                    while ($rowTable = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                                <td >' . $count. '</td>
                                <td >' . $rowTable['fullname'] . '</td>
                                <td >' . $zone_select . '</td>
                                <td >' . dateTimeToWords($rowTable['date_time']) . '</td>
                            </tr>';
                        $count += 1;
                    }

                    ?>
                </tbody>
            </table>
        </div>

        </div>
    <?php endif ?>