<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, true, 'customer');

// Fetch the invoice_id from the database
$invoice_id_query = "SELECT invoice_id FROM invoice WHERE user_id =" . $_SESSION["id"] ." and status = 'สำเร็จ'";
$invoice_id_result = mysqli_query($conn, $invoice_id_query);
?>
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
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
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
                                        class=" flex justify-center items-center gap-3 py-2">
                                        <i class="fi fi-tr-circle-user text-4xl"></i>
                                        <p>' . $_SESSION["user_username"] . '</p>
                                    </div>
                                </button>
                                
                                
                        <div id="dropdown2"
                        class="hidden absolute right-[-1.4rem] mt-6 w-48 bg-white text-black text-lg rounded-lg shadow-lg border border-gray-200 divide-y divide-gray-400"
                            <ul class="py-2">
                                <a href="history.php" class="flex px-4 py-2 items-center">
                                    <i class="fi fi-rs-ticket  px-2 text-red-600"></i>
                                    <p>ประวัติการจอง</p>
                                </a>
                            
                                <a href="logout.php" class="flex px-4 py-2 items-center">
                                    <i class="fi fi-bs-exit  px-2 text-red-600"></i>
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
                                    <i class="fi fi-tr-circle-user text-3xl" style="color: #191D88;"></i>
                                    <p>' . $_SESSION["user_username"] . '</p>
                                    </div>';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- <div class="md:flex md:items-center px-28 pt-32 pb-12">
        <p class="text-4xl text-[#000000] text-center"><b>ประวัติการจอง</b></p>
    </div> -->
    <div class="lg:block flex justify-center items-center w-full lg:w-fit lg:px-28 mt-32 ">
        <div class='rounded-2xl py-4 px-6 w-fit' style="background-color:#191D88;">
            <div class=" text-3xl  text-white font-bold">
                ประวัติการจอง
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-10 justify-center items-center px-5 py-12">
        <?php
        function dateTimeToWords($dateString)
        {
            $date = new DateTime($dateString);
            return $date->format('j F Y, เวลา g:i A');
        }

        function formatDate($dateString)
        {
            $date = new DateTime($dateString);
            return $date->format('j F Y');
        }

        function formatTime($dateString)
        {
            $date = new DateTime($dateString);
            return $date->format('g:i A');
        }
        if(mysqli_num_rows($invoice_id_result) == 0){
            echo'<div class="h-96"></div>';
        }
        while ($invoice_row = mysqli_fetch_assoc($invoice_id_result)) {
            $invoice_id = $invoice_row["invoice_id"];
            
            $sql = "SELECT e.name, e.poster, e.place, r.date_time, i.status , tc.tkc_name, i.total_price
            FROM invoice i
            JOIN ticket t USING (invoice_id)
            JOIN ticket_class tc ON (t.tkc_id = tc.tkc_id)
            JOIN event e USING (event_id)
            JOIN round r USING (round_id)
            WHERE i.invoice_id = '$invoice_id'
            GROUP BY i.invoice_id;";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $poster = $row['poster'];
                $name = $row['name'];
                $place = $row['place'];
                $datetime = $row['date_time'];
                $status = $row['status'];
                $tkc_name = $row['tkc_name'];
                $total_price = $row['total_price'];
                $sql = "SELECT concat(t.seat_col, t.seat_num) as seatfull
                FROM invoice i
                JOIN ticket t USING (invoice_id)
                WHERE i.invoice_id = '$invoice_id'";
                $result = mysqli_query($conn, $sql);
                $seat = '';
                $ticket_count = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $seat = $seat. $row['seatfull'].', ';
                    $ticket_count += 1;
                }
                $seat = substr($seat,0,-2);
                $id = $_SESSION['id'];
                echo '<div class="flex justify-center items-center lg:w-[965.4px] lg:h-[514.95px] sm:w-2/3 w-11/12 ">
        <div
            class="h-full w-full rounded-3xl bg-FAF3F0 shadow-md flex flex-col lg:flex-row bg-[#FAF3F0] overflow-hidden shadow-0px-4px-4px-0px ">
            <div class="lg:w-auto w-full text-center lg:min-w-fit  flex items-center justify-center ">
                <img src=' . $poster . ' alt="" class="h-full w-full">
            </div>
            <div class="w-full flex-1 p-10 flex flex-col gap-6 justify-between">
                <div class="flex flex-col gap-4 ">
                <div class="">
                    <h2 class=" text-left lg:text-4xl text-3xl font-bold ">' . $name . '</h2>
                </div>
                <div class=" flex gap-6">
                    <i class="fi fi-rr-calendar text-4xl"></i>
                    <p class="md:text-xl text-lg"><b>วันที่แสดง</b><br>
                    ' . formatDate($datetime) . '</p>
                </div>
                <div class=" flex gap-6">
                    <i class="fi fi-rr-marker sm:text-4xl text-3xl"></i>
                    <p class="md:text-xl text-lg"><b>สถานที่แสดง</b><br>' . $place . '</p>
                </div>
                <div class=" flex gap-6">
                    <i class="fi fi-rr-clock-five text-4xl"></i>
                    <p class="md:text-xl text-lg"><b>รอบการแสดง</b><br>' . formatTime($datetime) . '</p>
                </div>
                <div class=" flex gap-6">
                    <i class="fi fi-rr-bookmark text-4xl"></i>
                    <p class="md:text-xl text-lg"><b>สถานะ : </b>' . $status . '</p>
                </div>
                </div>
                <div>
                <div class="flex xl:gap-6 gap-3 justify-center sm:flex-row items-center flex-col">
                    <a href="refund.php?inv=' . urlencode($invoice_id) . '" class="w-fit">
                    <button
                        class="bg-[#BB2525]  text-white font-bold lg:text-xl text-lg py-3 px-5 rounded-2xl w-40 lg:w-44 xl:px-6">
                        ยกเลิกการจอง
                    </button>
                    </a>
                    <!-- ปุ่มดูรายละเอียด -->
                    <div class="w-fit">
                    <button
                        class="bg-[#191D88]   text-white font-bold lg:text-xl text-lg py-3 px-5 rounded-2xl w-40 lg:w-44  xl:px-6 showModalButton">
                        ดูรายละเอียด
                    </button>
                    </div>
                </div>
                <!-- Modal Pop-up -->
                <div class="fixed z-50 inset-0 flex items-center justify-center hidden modalOverlay p-2"
                    style="background-color: rgba(223,229,240,0.7);">
                    <div class="modal bg-white w-full min-w-fit lg:w-1/3 sm:p-8 p-6 rounded-md shadow-md flex flex-col items-center overflow-hidden">
                        <div class="lg:flex lg:items-center items-left px-8  pb-4">
                            <p class="p-4 text-center text-2xl text-[#000000] break-words">' . $name . '</b>
                            </p>
                        </div>
                        <div class="lg:items-center lg:px-10">
                            <div class="flex justify-between border-b-2 border-[#B4B4B3] mb-10 text-xl gap-10">
                                <p class="font-bold">รอบการแสดง</p>
                                <p class="">' . dateTimeToWords($datetime) . '</p>
                            </div>
                            <div class="flex justify-between border-b-2 border-[#B4B4B3] mb-10 text-xl">
                                <p class="font-bold">โซนที่นั่ง</p>
                                <p class="">' . $tkc_name . '</p>
                            </div>
                            <div class="flex justify-between border-b-2 border-[#B4B4B3] mb-10 text-xl">
                                <p class="font-bold">เลขที่นั่ง</p>
                                <p class="">' . $seat . '</p>
                            </div>
                            <div class="flex justify-between border-b-2 border-[#B4B4B3] mb-10 text-xl">
                                <p class="font-bold">จำนวนที่นั่ง</p>
                                <p class="">' . $ticket_count . '</p>
                            </div>
                            <div class="flex justify-between border-b-2 border-[#B4B4B3] mb-10 text-xl">
                                <p class="font-bold">ราคาบัตร</p>
                                <p class="">' . $total_price . '</p>
                            </div>
                        </div>
                        <!-- ปุ่มปิด Modal -->
                        <div class="flex-grow"></div>
                        <button class="bg-red-500 text-xl   text-white font-bold py-2 px-4 rounded-2xl mb-4 closeModalButton">
                            ปิด
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>';
            }
        }
        ?>
    </div>

    <script>
        const viewDetailsButtons = document.querySelectorAll('.showModalButton');
        const closeModalButtons = document.querySelectorAll('.closeModalButton');
        const modalOverlays = document.querySelectorAll('.modalOverlay');

        viewDetailsButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                modalOverlays[index].classList.remove('hidden');
            });
        });

        closeModalButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                modalOverlays[index].classList.add('hidden');
            });
        });

        modalOverlays.forEach((overlay) => {
            overlay.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    overlay.classList.add('hidden');
                }
            });
        });
    </script>


    <!-- footer -->
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
    <script src='dropdown_navorg.js'></script>
</body>

</html>