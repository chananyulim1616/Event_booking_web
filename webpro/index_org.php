<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, true, 'organizer');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com/3.3.3?plugins=line-clamp@0.4.4"></script>
    <title>iTicket</title>
    <link rel="icon" type="image/x-icon" href="img/lillogo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
    </style>
</head>

<body class="relative">
    <div id='modal'></div>
    <nav class="fixed top-0 w-full z-20 px-4 py-3" style="background-color:#272829;">
        <div class="w-full mx-auto">
            <div class="hidden text-xl items-center md:flex justify-between w-full text-white h-16 px-4 ">
                <div class=" h-full">
                    <img class="h-full" src="img/logo.png" alt="logo"></a>
                </div>
                <!-- icon user -->
                <div class='flex items-center'>
                    <div class='flex items-center'>
                        <i class="fi fi-tr-house-chimney text-4xl pt-2 text-white"
                            onclick="window.location.href='index_org.php';" style="cursor: pointer;"></i>
                    </div>
                    <div class="relative inline-block text-left ml-auto">
                        <button id="userLink2" data-dropdown-toggle="dropdownDefaultButton2"
                            class="flex inline-flex items-center justify-between w-full font-medium text-sm px-5 py-2.5 text-center rounded-lg focus:outline-none focus:shadow-outline">
                            <div class="mt-2">
                                <i class="fi fi-tr-circle-user text-4xl" style="color:white;"></i>
                            </div>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdown2" class="hidden absolute right-0 mt-3.5 w-44 bg-white text-black text-lg rounded-lg shadow-lg
                             border-2 border-gray-300 divide-y divide-gray-400">
                            <ul class="py-2 ">
                                    <a href="logout.php" class="flex justify-center items-center">
                                        <i class="fi fi-bs-exit px-2 pt-1 text-red-600"></i>
                                        <p>ออกจากระบบ</p>
                                    </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:hidden flex flex-col z-30">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="flex inline-flex text-xl items-center justify-between w-full text-white h-16 px-4 text-white  font-medium text-sm px-5 py-2.5 text-center  items-center "
                    type="button">
                    <div class='flex items-center'>
                        <i class="fi fi-rr-menu-burger pt-2"></i>
                    </div>
                    <div class="h-full">
                        <img class="h-full" src="img/logo.png" alt="logo"></a>
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown" style="border-radius: 0 0 0.5rem 0.5rem;"
                    class=" hidden overflow-hidden divide-y  shadow w-full ">
                    <ul class="py-2 text-md bg-white text-[#272829]" aria-labelledby="dropdownDefaultButton">
                        <li>
                            <a href="index_org.php" class="block  px-4 py-2 ">หน้าหลัก</a>
                        </li>
                    </ul>
                    <div class="py-2 text-md bg-white" style="color:#191D88" aria-labelledby="dropdownDefaultButton">
                        <div id="user" onclick="user()" class="block px-4 py-2  flex items-center gap-3">
                            <i class="fi fi-tr-circle-user text-3xl pt-1" style="color: #272829;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="z-0 overflow-hidden h-128 w-full flex flex-col justify-center items-center md:px-32 sm:px-24 px-10 md:pt-48
     pt-52 pb-40">
        <img class="sm:shrink-0" src="img/orglogo.png" alt="">
        <p class ='sm:text-3xl text-center text-xl'style="color: #272829;">"Discover, Experience, Celebrate - Your Event Hub!"</p>
    </div>
    <div class="min-w-full flex flex-col  xl:px-32 xl:pb-32 md:px-16 md:pb-16 px-8 pb-8 ">
        <div
            class='grid 2xl:grid-cols-4 xl:grid-cols-3  md:grid-cols-2 grid-cols-1  justify-items-center gap-y-8 w-full mb-14'>
            <div class='flex  w-80 2xl:w-72'>
                <div class='rounded-2xl p-4 ' style="background-color:#272829;">
                    <div class="lg:text-xl text-lg text-white font-bold">
                        กิจกรรมทั้งหมด
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-colsitems-center" id='event'>
            <?php

            $sql = 'SELECT event_id,name, poster, sale_date from event where org_id = ' . $_SESSION["id"] . ' ORDER BY event_id';
            
            $result = mysqli_query($conn, $sql);
            echo "<div class='grid 2xl:grid-cols-4 xl:grid-cols-3 md:grid-cols-2 grid-cols-1  justify-items-center gap-y-11 2xl:gap-y-9 w-full'>";
            $count = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $eid = $row['event_id'];
                $pos = $row['poster'];
                $na = $row['name'];
                $id = $_SESSION['id'];
                $count += 1;
                $sql2 = "SELECT min(date_time) as start FROM event join round using (event_id) WHERE event_id = $eid";
                $start = mysqli_fetch_assoc(mysqli_query($conn, $sql2))['start'];
                echo "<div style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);' id='event_"
                    . $count . "'class='relative rounded-xl'>
                    <div class= 'gap-y-2  p-8 rounded-xl w-80 2xl:w-72 text-xs md:text-md  xl:text-lg flex justify-between items-center flex-col pb-5'>";
                    $now = new DateTime();
                    $sale_date = new DateTime($row['sale_date']);
                    $start_date = new DateTime($start);
                if ($now < $start_date and $now <$sale_date) {
                    echo "<button type='button' id='" . $count . "' onclick='del_event(id)' class=' absolute top-[-0.75rem] right-[-0.75rem]  text-zinc-500 hover:text-red-600'>
                        <i class='fi fi-br-circle-xmark text-2xl '></i>
                    </button>";
                }
                echo "<a class='w-full' href='view_ticket.php?eventid=$eid'>
                        <img class='transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-105 duration-300 rounded-lg mb-2' style='box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);' src='$pos'></img>
                        <div class='w-full text-lg font-bold h-16 flex justify-center items-center'>
                            <div class='text-center h-full line-clamp-2 'id='p_name'>$na</div>
                        </div>
                    </a>";
                    if ($now < $start_date and $now <$sale_date) {
                    echo"<div class='flex'>
                        <a class='text-base w-fit flex justify-center items-center gap-1.5 hover:cursor-pointer py-3 px-5 rounded-xl text-white'
                        style='background-color:#272829;' href ='edit.php?eventid=$eid'>
                            <i class=' fi fi-rr-pencil pt-1'></i>
                            <p class='font-bold'>แก้ไข</p>
                        </a>
                    </div>";}
                    else{
                        echo"<div class='flex'>
                        <a class='text-base w-fit flex justify-center items-center gap-1.5 hover:cursor-pointer py-3 px-5 rounded-xl text-white'
                        style='background-color:#272829;' >
                            <p class='font-bold'>เปิดขายแล้ว</p>
                        </a>
                    </div>";
                    }
                   echo" </div>
                </div>";
            }
            ;
            ?>
            <a href="create.php">
                <div style=" background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);"
                    class="h-[538.19px] 2xl:h-[501.6px] transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-105 duration-300 p-8 rounded-xl w-80 2xl:w-72 text-xs md:text-md  xl:text-lg flex justify-center items-center flex-col pb-5">
                    <img src="img/add.png" class='w-full h-fit' alt="">

                </div>
            </a>
        </div>
    </div>
    </div>

    <footer>
        <div class="bg-[#272829] flex w-full sm:flex-row flex-col sm:items-start items-center p-5 gap-5">
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
    <script>
        function retry() {
            var element = document.getElementById("pop");
            element.remove();
        }

        function del_event(loc_e) {
            var modal = document.getElementById('modal')
            modal.innerHTML +=
                `<div id ="pop" class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="fixed inset-0 z-50 w-screen overflow-y-auto" onclick="retry()">
                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">

                            <div class="px-4 pb-4 pt-5 sm:p-7 sm:pb-6 relative transform overflow-hidden rounded-lg bg-white 
                            text-left shadow-xl transition-all sm:my-8 w-fit sm:max-w-lg">
                                <div class="bg-white flex flex-col gap-4">
                                    <p class ='font-semibold text-xl'>คุณแน่ใจหรือไม่ที่จะลบอีเว้นท์นี้ทิ้ง</p>
                                    <div class='flex justify-center items-center gap-4 text-lg'>
                                        <button class ='px-4 pt-0.5 font-semibold rounded-lg bg-red-600 text-white items-center' 
                                        onclick='del_event_confirm(${loc_e})'>
                                            <p>ยืนยัน</p>
                                        </button>
                                        <button class ='px-4 pt-0.5 font-semibold rounded-lg bg-red-600 text-white items-center' onclick='retry()'>
                                            <p>ยกเลิก</p>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div >`
        }

        function del_event_confirm(loc_e) {
            var block = document.getElementById('event_' + loc_e)
            $.ajax({
                type: 'POST',
                url: 'delete_event.php',
                data: {
                    loc_e: loc_e
                },
                success: function (response) {
                    if (response == 'success') {
                        block.remove()
                        retry()
                    }
                },
                error: function () {
                    console.error('Error sending data to PHP');
                }
            });

        }
        function user() {
                var modal = document.getElementById('modal')
                modal.innerHTML +=
                `<div id ="pop" class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="fixed inset-0 z-50 w-screen overflow-y-auto" onclick="retry()">
                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                        <a href="logout.php">
            <div
                class="px-4 py-4 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-fit sm:max-w-lg" >
                <div class="bg-white ">
                <div class ='flex gap-3 items-center'>
                <i class="fi fi-bs-exit  text-red-600"></i>
                <p>ออกจากระบบ</p>
                </div>
                </div>
            </div>
            </a>
                        </div>
                    </div>
                </div >`
            };
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
    <script src='dropdown_navorg.js'></script>
</body>

</html>