<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, true, 'customer');
// Start the session (if not already started)
$user_id = $_SESSION["id"];
$invoice_id = $_GET['inv'];
if (isset($_POST['submit'])) {

    $delete_invoice_sql = "UPDATE ticket AS t 
JOIN invoice AS i 
USING (invoice_id) 
SET t.invoice_id = NULL 
WHERE i.invoice_id = '$invoice_id' AND i.user_id = $user_id;";

    $ud_status =
        "update invoice 
set status = 'ยกเลิก'
WHERE invoice_id = '$invoice_id' AND user_id = $user_id;";

    mysqli_query($conn, $ud_status);
    mysqli_query($conn, $delete_invoice_sql);
    header("Location: history.php");
    exit;
}
$sql = "SELECT invoice_id FROM invoice WHERE user_id = $user_id and invoice_id ='$invoice_id' and status = 'สำเร็จ'";
if (mysqli_num_rows(mysqli_query($conn, $sql)) != 1) {
    header("Location: history.php");
    exit;
}
?>


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
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var dropdownButton = document.getElementById("dropdownDefaultButton");
            var dropdownMenu = document.getElementById("dropdown");

            dropdownButton.addEventListener("click", function () {
                dropdownMenu.classList.toggle("hidden");
            });
        });

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
                                <div class="bg-white ">
                                <a class ='flex gap-3 items-center ' href='register.php'>
                                <i class="fi fi-rs-ticket pt-1 text-red-600"></i>
                                <p>ประวัติการจอง</p>
                                
                                </a>
                                <div class ='flex gap-3 items-center'>
                                <i class="fi fi-bs-exit pt-1 text-red-600"></i>
                                <p>ออกจากระบบ</p>
                                </div>
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
    <div class=" flex justify-center items-center w-full   mt-32 ">
        <div class='rounded-2xl py-4 px-6 w-fit' style="background-color:#191D88;">
            <div class=" text-3xl  text-white font-bold">
                เงื่อนไขการยกเลิกการจอง
            </div>
        </div>
    </div>
    <div class='w-full flex justify-center items-center py-12 '>
        <form method="post" class='w-fit flex flex-col gap-6 text-xl'>
            <div class="">
                <p class="">สามารถยกเลิกการจองการบัตรเข้าชมก่อนวันทำการแสดง 30 วัน</p>
            </div>
            <div class="flex flex-col gap-2">
                <p ><b>เอกสารที่ต้องใช้</b> ดังนี้</p>
                <p>1. บัตรการแสดง (หากยังไม่ได้รับบัตร ให้แสดงใบยืนยันการสั่งซื้อแทน)</p>
                <!-- input รูป -->
                <div class="max-w-xs">
                    <div class="flex items-center space-x-4">
                        <div class="mt-1 flex-1">
                            <input type="file" id="img" name="img" accept="image/*"
                                class="py-2 px-3 border border-gray-300 w-full" required>
                        </div>
                    </div>
                </div>
                <p class=" text-[#000000]">2. บัตรประชาชนผู้ซื้อ</p>
                <!-- input รูป -->
                <div class="max-w-xs ">
                    <div class="flex items-center space-x-4">
                        <div class="mt-1 flex-1">
                            <input type="file" id="img" name="img" accept="image/*"
                                class="py-2 px-3 border border-gray-300 w-full" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <p class=" text-[#000000]">
                    <b>เงื่อนไขการคืนเงิน</b>&nbsp&nbsp&nbsp&nbspคืนวงเงินกลับยังบัตรเครดิต,
                    เดบิตใบเดิม ภายใน 2 รอบบิล</p>
            </div>
            <div class="flex justify-center items-center">

                <button type="submit"
                    class="text-xl text-white font-bold w-fit rounded-xl py-2 px-6 bg-red-600"
                    name="submit">ยืนยัน</button>
            </div>
        </form>
    </div>
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