<?php

session_start();
require_once("dbconfig.php");

$login = checklogin($conn, false, 'customer');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>

    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }

        .animate-slidein {
            opacity: 1;
            transform: translateX(0);
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
                                    <i class="fi fi-rs-ticket pt-1 text-red-600"></i>
                                    <p>ประวัติการจอง</p>
                                </a>
                                <a class ='flex gap-3 items-center ' href='logout.php'>
                                    <div class ='flex gap-3 items-center'>
                                    <i class="fi fi-bs-exit pt-1 text-red-600"></i>
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
                        echo'<div class="relative inline-block text-left">
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
                            <a href="index.php" class="block  px-4 py-2 ">หน้าหลัก</a>
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
    <div class="z-0 overflow-hidden h-128 w-full flex flex-col justify-center items-center md:px-32 sm:px-24 px-10 md:pt-48
     pt-52 pb-40">
        <img class="sm:shrink-0" src="img/logoblue.png" alt="">
        <p class='sm:text-3xl text-center text-xl' style="color: #1450A3;">"Discover, Experience, Celebrate - Your Event
            Hub!"</p>
    </div>
    <div class="min-w-full flex flex-col  xl:px-32 xl:pb-32 md:px-16 md:pb-16 px-8 pb-8">
        <div
            class='grid 2xl:grid-cols-4 xl:grid-cols-3  md:grid-cols-2 grid-cols-1  justify-items-center gap-y-8 w-full mb-14'>
            <div class='flex  w-80 2xl:w-72'>
                <div class='rounded-2xl p-4 ' style="background-color:#191D88;">
                    <div class="lg:text-xl text-lg text-white font-bold">
                        กิจกรรมทั้งหมด
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-colsitems-center " id='event'>
            <?php
            $sql = 'SELECT event_id,name, poster as "poster" from event';
            $result = mysqli_query($conn, $sql);
            if(isset($_GET['type'])){
                $sql = 'SELECT event_id,name, poster as "poster" from event where type = "'.$_GET['type'].'"';
            $result = mysqli_query($conn, $sql);
            }
            echo "<div class='grid 2xl:grid-cols-4 xl:grid-cols-3 md:grid-cols-2 grid-cols-1  justify-items-center gap-y-11 2xl:gap-y-9 w-full'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);' class ='transition ease-in-out delay-150 
                hover:-translate-y-1 hover:scale-105 duration-300 p-8 rounded-xl w-80 2xl:w-72 text-xs md:text-md  xl:text-lg flex justify-between 
                items-center flex-col pb-5'>";
                echo "<a href='event.php?eventid=" . $row['event_id'] . "'>";
                echo "<img class='rounded-lg'style='box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);' src='" . $row['poster'] . "'>";
                echo "<div class='text-lg  font-bold h-20 pt-5 flex justify-center item-center'><p class='text-center line-clamp-2'>"
                . $row['name'] . "</p></div>";
                echo "</a>";
                echo "</div>";
            }
            ;
            ?>
        </div>
    </div>
    </div>

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
        <script src='dropdown_navorg.js'></script>
</body>

</html>