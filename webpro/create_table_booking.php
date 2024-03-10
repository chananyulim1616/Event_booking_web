<?php
require_once("dbconfig.php");
session_start();
$eid = $_POST['eid'];

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
$sql = "SELECT tkc_name, ticket_id, seat_num, invoice_id, seat_col FROM ticket join ticket_class using (tkc_id) where event_id = $eid and tkc_name = '$zone_select' and round_id=$round_select order by price ;";

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

$sql = "SELECT COUNT(ticket_id) as max FROM ticket JOIN ticket_class using(tkc_id) where invoice_id IS NULL and round_id = $round_select and tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$max = mysqli_fetch_assoc($result)['max'];

if ($max >= 6) {
    $max = 6;
}
$sql = "SELECT capacity FROM ticket_class where tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$capacity = mysqli_fetch_assoc($result)['capacity'];
mysqli_close($conn);
?>
<style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
        .remove-arrow::-webkit-inner-spin-button, 
        .remove-arrow::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        } 
  
        .remove-arrow { 
            -moz-appearance: textfield; 
        } 
    </style>
<?php

if ($zones[$zone_select][1] != 0): ?>
    <div class="mx-14 md:mx-20 my-12 flex flex-col gap-4" id='result'>
        <div class="p-3 font-bold text-white rounded-2xl bg-[#191D88] w-fit">
            <p>แผนผังที่นั่ง</p>
        </div>
        <div
            class=" gap-10 justify-items-center flex justify-around flex-col md:flex-row  ">

            <div class="flex-col md:w-8/12 ">
                <div>
                    <div class="movie-container">
                        <div class="flex pb-2 pt-3 border-2 border-solid rounded-lg justify-around">
                            <div class="flex flex-col justify-center items-center">
                                <div class="seat"></div>
                                <p>ที่นั่งที่ว่าง</p>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <div class="seat selected"></div>
                                <p>ที่นั่งที่เลือก</p>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <div class="seat occupied"></div>
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
                            echo "<div class='flex gap-2  items-center' id='row3'><p class='h-6 border-solid border-2 rounded-lg text-base 
                            w-8 text-center'>" . $col_name[$rows] . "</p></div>";
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
                                        echo "<div class='seat occupied'";
                                    } else {
                                        echo "<div class='seat'";
                                    }
                                    echo "id= '" . $seats[$count][1] . "' '></div>";
                                    $count++;
                                    if ($count >= $capacity) {
                                        break 2;
                                    }

                                } else {
                                    echo "<div class='noseat'></div>";
                                }
                            }
                            echo "</div>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-fit">
        <div class="xs:m-4 px-10 py-8 w-full rounded-xl space-y-4 h-fit "
            style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);'>
            <p class="font-bold text-center md:text-2xl text-xl">รายละเอียดการจอง</p>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-6 md:gap-18 lg:gap-18">
                <p class="font-bold ">รอบการแสดง</p>
                <p id='round_name'></p>
            </div>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                <p class="font-bold ">โซนที่นั่ง</p>
                <p id='ticket_type'></p>
            </div>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                <p class="font-bold ">เลขที่นั่ง</p>
                <p id='NO_seat_selected'>-</p>
            </div>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                <p class="font-bold ">จำนวนที่นั่ง</p>
                <p id='total_seat'>0</p>
            </div>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                <p class="font-bold ">ราคาบัตร</p>
                <p id='price_zone'>0</p>
            </div>
            <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                <p class="font-bold ">ราคาทั้งหมด</p>
                <p id='total'>0</p>
            </div>
        </div>
    </div>
    </div>

<?php endif ?>

<?php if ($zones[$zone_select][1] == 0): ?>
    <div id='result'>

        <div class="flex justify-center m-6">
            <div class="xs:m-4 px-10 py-8 w-fit rounded-xl space-y-4 h-fit "
                style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);'>
                <p class="font-bold text-center md:text-2xl text-xl">รายละเอียดการจอง</p>
                <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-6 md:gap-18 lg:gap-18">
                    <p class="font-bold ">รอบการแสดง</p>
                    <p id='round_name'></p>
                </div>
                <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                    <p class="font-bold ">โซนที่นั่ง</p>
                    <p id='ticket_type'></p>
                </div>
                <?php if ($max > 0): ?>
                    <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                        <p class="font-bold ">ราคาบัตร</p>
                        <p id='price_zone'>0</p>
                    </div>
                    <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                        <p class="font-bold ">จำนวนที่นั่ง</p>
                        <!-- component -->
                        <div class="custom-number-input h-5 w-20">
                            <div class="flex flex-row h-5 w-20 rounded-lg relative bg-transparent mt-1">
                                <button id='minus' onclick="minus()"
                                    class="overflow-hidden flex justify-center items-center bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-l cursor-pointer outline-none">
                                    <span class=" text-xl font-thin text-center ">−</span>
                                </button>
                                <input id='total_seat' type="number" min="1" max="<?php echo $max ?>" value="1"
                                    class="p-1 focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700 outline-none"></input>
                                <button id='plus' onclick="plus()"
                                    class="overflow-hidden flex justify-center items-center bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-r cursor-pointer">
                                    <span class="text-xl font-thin ">+</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                        <p class="font-bold ">ราคาทั้งหมด</p>
                        <p id='total'>0</p>
                    </div>

                <?php else: ?>
                    <div class="flex justify-between border-b-2 border-[#B4B4B3] gap-18">
                        <p class="font-bold ">จำนวนที่นั่ง</p>
                        <p class="font-bold text-red-600">จำหน่ายหมดแล้ว</p>
                    </div>

                <?php endif ?>
            </div>
        </div>

    <?php endif ?>