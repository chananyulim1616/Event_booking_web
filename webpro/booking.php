<?php
session_start();
require_once("dbconfig.php");
$login = checklogin($conn, true, 'customer');

$eid = $_GET['eventid'];
$sql = "SELECT sale_date,min(date_time) as start FROM event join round using (event_id) WHERE event_id = $eid";
$result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
$now = new DateTime();
$sale_date = new DateTime($result['sale_date']);
$start_date = new DateTime($result['start']);
if ($now > $start_date or $now < $sale_date) {
    header("location: event.php?eventid=$eid");
}

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

if (isset($_POST['zoneSelect'])) {
    $zone_select = $_POST["zoneSelect"];

} else {
    $zone_select = array_keys($zones)[0];
}
if (isset($_POST['roundSelect'])) {
    $round_select = $_POST["roundSelect"];

} else {
    $round_select = array_keys($rounds)[0];
}

$sql = "SELECT tkc_name,COUNT(DISTINCT(seat_col)) as zone_num FROM ticket join ticket_class using (tkc_id) 
where event_id = $eid group by tkc_id order by price ;";
$result = mysqli_query($conn, $sql);
$zone_num = array();
while ($rows = mysqli_fetch_assoc($result)) {
    $zone_num[$rows['tkc_name']] = $rows['zone_num'];
}

$sql = "SELECT tkc_name, ticket_id,seat_num, invoice_id, seat_col FROM ticket join ticket_class using (tkc_id) 
where event_id = $eid and tkc_name = '$zone_select' and round_id=$round_select order by price ;";
$result = mysqli_query($conn, $sql);
$seats = array();
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($seats, array(
        $rows['tkc_name'],
        $rows['ticket_id'],
        $rows['seat_num'],
        $rows['invoice_id'],
        $rows['seat_col']
    )
    );
}

$sql = "SELECT DISTINCT(seat_col) as col_name FROM ticket join ticket_class using (tkc_id)
where tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$col_name = array();
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($col_name, $rows['col_name']);
}

$sql = "SELECT COUNT(ticket_id) as max FROM ticket JOIN ticket_class using(tkc_id) where invoice_id IS NULL 
and round_id = $round_select  and tkc_name = '$zone_select' and event_id = $eid;";
$result = mysqli_query($conn, $sql);
$max = mysqli_fetch_assoc($result)['max'];
if ($max >= 6) {
    $max = 6;
}

$sql = "SELECT capacity FROM ticket_class where tkc_name = '$zone_select' and event_id = $eid";
$result = mysqli_query($conn, $sql);
$capacity = mysqli_fetch_assoc($result)['capacity'];
$sql = "SELECT * FROM event where event_id = $eid";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>iTicket</title>
    <link rel="icon" type="image/x-icon" href="img/lillogo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css'>
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
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
    <link rel="stylesheet" href='style2.css' />
    <script>
        function retry() {
            var element = document.getElementById("pop");
            element.remove();
        }
        function user() {
            var modal = document.getElementById('modal')
            modal.innerHTML +=
                `<div id ="pop" class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="fixed inset-0 z-50 w-screen overflow-y-auto" onclick="retry()">
                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">

                            <div
                                class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-fit sm:max-w-lg">
                                <div class="bg-white flex flex-col gap-2 ">
                                <a class ='flex gap-3 items-center ' href='history.php'>
                                    <i class="fi fi-rs-ticket  text-red-600"></i>
                                    <p>ประวัติการจอง</p>
                                </a>
                                <a class ='flex gap-3 items-center ' href='logout.php'>
                                    <div class ='flex gap-3 items-center'>
                                    <i class="fi fi-bs-exit  text-red-600"></i>
                                    <p>ออกจากระบบ</p>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
        </div >`
        };
    </script>

</head>

<body class="relative">
    <div id='modal'></div>
    <nav class=" fixed top-0 w-full z-20 p-4" style="background-color:#191D88;">
        <div class="w-full mx-auto">
            <div class="hidden text-xl items-center lg:flex justify-between w-full text-white h-16 px-4 ">
                <div class=" h-full">
                    <img class="h-full" src="img/logo.png" alt="logo"></a>
                </div>
                <div class="hidden lg:flex gap-16">
                    <a class="" href="index.php">หน้าหลัก</a>
                    <a class="" href="index.php?type=นิทรรศการ">นิทรรศการ</a>
                    <a class="" href="index.php?type=อีเว้นท์">อีเว้นท์</a>
                    <a class="" href="index.php?type=คอนเสิร์ต">คอนเสิร์ต</a>
                </div>

                <div class='border rounded-xl px-4 py-2 text-lg'>
                    <?php
                    if (!$login) {
                        echo '<a href="login.php">เข้าสู่ระบบ/สมัครสมาชิก</a>';
                    } else {
                        echo '<div class="relative inline-block text-left">
                                <button id="userLink2" data-dropdown-toggle="dropdown">
                                    <div id = "user"
                                        class=" flex justify-center items-center gap-3">
                                        <i class="fi fi-tr-circle-user text-4xl pt-2"></i>
                                        <p>' . $_SESSION["user_username"] . '</p>
                                    </div>
                                </button>
                                
                                
                        <div id="dropdown2"
                        class="hidden absolute right-[-1.4rem] mt-6 w-48 bg-white text-black text-lg rounded-lg shadow-lg border border-gray-200 divide-y divide-gray-400"
                            <ul class="py-2">
                                <a href="history.php" class="flex px-4 py-2 items-center">
                                    <i class="fi fi-rs-ticket pt-1 px-2 text-red-600"></i>
                                    <p>ประวัติการจอง</p>
                                </a>
                            
                                <a href="logout.php" class="flex px-4 py-2 items-center">
                                    <i class="fi fi-bs-exit pt-1 px-2 text-red-600"></i>
                                    <p>ออกจากระบบ</p>
                                </a>
                            </ul>
                            </div>
                    </div>';

                    }
                    ?>
                </div>

            </div>
            <div class="lg:hidden sm:flex flex-col z-30">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="flex inline-flex text-xl items-center  justify-between w-full text-white h-16 px-4 text-white  font-medium text-sm px-5 py-2.5 text-center  items-center "
                    type="button">
                    <div>
                        <i class="fi fi-rr-menu-burger"></i>
                    </div>
                    <div class=" h-full">
                        <img class="h-full" src="img/logo.png" alt="logo"></a>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown" style="border-radius: 0 0 0.5rem 0.5rem;"
                    class=" hidden overflow-hidden divide-y  shadow w-full ">
                    <ul class="py-2 text-md bg-white" style="color:#191D88" aria-labelledby="dropdownDefaultButton">
                    <li>
                            <a href="index.php" class="block px-4 py-2">หน้าหลัก</a>
                        </li>
                        <li>
                            <a href="index.php?type=นิทรรศการ" class="block px-4 py-2 ">นิทรรศการ</a>
                        </li>
                        <li>
                            <a href="index.php?type=อีเว้นท์" class="block px-4 py-2 ">อีเว้นท์</a>
                        </li>
                        <li>
                            <a href="index.php?type=คอนเสิร์ต" class="block px-4 py-2 ">คอนเสิร์ต</a>
                        </li>
                    </ul>
                    <div class="py-2 text-md bg-white" style="color:#191D88" aria-labelledby="dropdownDefaultButton2">
                        <?php
                        if (!$login) {
                            echo '<a href="login.php"
                                    class="block px-4 py-2 "
                                    >เข้าสู่ระบบ/สมัครสมาชิก</a>';
                        } else {
                            echo '<div id = "user" onclick="user()"
                                    class="block px-4 py-2  flex items-center gap-3">
                                    <i class="fi fi-tr-circle-user text-3xl pt-1" style="color: #191D88;"></i>
                                    <p>' . $_SESSION["user_username"] . '</p>
                                    </div>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class=" w-full h-full  bg-cover bg-center bg-[url(<?= $row['poster'] ?>)]">
        <div class='flex items-center justify-center p-12 h-full w-screen backdrop-blur-md bg-black bg-opacity-40'>
            <div
                class='items-center w-10/12 justify-center h-full gap-8  xs:flex-col grid justify-items-center md:flex justify-center lg:md:flex justify-center xl:md:flex justify-center mt-20'>
                <div class="">
                    <img class="w-[200px] " src="<?= $row['poster'] ?>" alt="logo789">
                </div>
                <div class="p-4 flex flex-col gap-3">
                    <p class="font-bold text-white text-2xl xs:text-sm line-clamp-2">
                        <?= $row['name'] ?>
                    </p>
                    <p class="text-lg mb-2  text-white"><a href="event.php?eventid=<?php echo $eid ?>">รายละเอียด ></a>
                    </p>
                    <div class='border-b-2'></div>
                    <div class="flex mt-2 gap-2">
                        <label for="round" class="text-lg text-white font-bold ">รอบการแสดง:</label>
                        <select id="round"
                            class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block">
                        <?php
                            foreach ($rounds as $x => $val) {
                                if ($x == $round_select) {
                                    echo "<option selected value='$x' >$val</option>";
                                } else {
                                    echo "<option value='$x' >$val</option>";
                                }

                            } ?>
                        </select>
                    </div>
                    <div class="flex mt-2 gap-2">
                        <label for="zone" class="text-lg text-white font-bold ">ประเภทบัตรบัตรเข้าชม:</label>
                        <select id="zone"
                            class=" border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block">
                            <?php
                            foreach ($zones as $x => $val) {
                                if ($x == $zone_select) {
                                    echo "<option selected value='$x' >$x($val[0]\$)</option>";
                                } else {
                                    echo "<option value='$x' >$x($val[0]\$)</option>";
                                }

                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    if ($zones[$zone_select][1] != 0): ?>
        <div class="mx-14 md:mx-20 my-12 flex flex-col gap-4" id='result'>
            <div class="p-3 font-bold text-white rounded-2xl bg-[#191D88] w-fit">
                <p>แผนผังที่นั่ง</p>
            </div>
            <div class=" gap-10 justify-items-center flex justify-around flex-col md:flex-row  ">

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
                                echo "<div class='flex gap-2  items-center' id='row3'><p class='h-6 border-solid border-2 rounded-lg text-base w-8 text-center'>" 
                                . $col_name[$rows] . "</p></div>";
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
        </div>
    <?php endif ?>
    <?php if ($zones[$zone_select][1] == 0): ?>
        <div id='result'>
            <div class="flex justify-center m-6">
                <div class="xs:m-4 px-10 py-12 w-fit rounded-xl space-y-4 h-fit "
                    style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);'>
                    <p class="font-bold text-center text-2xl text-lg">รายละเอียดการจอง</p>
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
        </div>

    <?php endif ?>
    <div class="mb-6  mt-6 flex justify-center">
        <button id='payment'
            class="px-4 rounded-3xl bg-[#2BAF2B] font-bold text-xl h-14 text-white w-44">ยืนยัน</button>
    </div>
    <footer>
        <div class="bg-[#191D88] flex w-full sm:flex-row flex-col  sm:items-start items-center p-5 gap-5">
            <div class=" sm:shrink-0">
                <img src="img/logo.png" alt="logo">
            </div>
            <div class='flex sm:text-left text-center'>
                <p class="text-white">หากคุณมีข้อสงสัยหรือติดปัญหาเกี่ยวกับการใช้งานระบบ สามารถติดต่อ
                    ทุกวันจันทร์-อาทิตย์ 10:00 – 18:00 น. <br>
                    อีเมล : iticketthailand@gmail.com <br>
                    เบอร์โทรศัพท์ : 061-123-4567 </p>
            </div>
        </div>
    </footer>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- old -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function clampText(selector, lines) {
                const elements = document.querySelectorAll(selector);
                var count = 0
                elements.forEach((element) => {
                    const lineHeight = parseFloat(getComputedStyle(element).lineHeight);
                    const maxHeight = lineHeight * lines;
                    element.style.maxHeight = `${maxHeight}px`;
                    element.style.overflow = 'hidden';
                    if (!isOverflown(element)) {
                        element.classList += ' flex items-center';
                    }
                    count += 1
                });
            }

            // Call the function with your selector and desired line count

            function isOverflown(element) {
                console.log(element.scrollHeight, element.clientHeight)
                return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
            }
            clampText('.line-clamp-2', 2); // Adjust the selector and line count as needed
        });
    </script>


    <!--Datatables -->
    <?php
    require_once('script_booking_cus.php');
    ?>

    <script src='dropdown_navorg.js'></script>
</body>

</html>