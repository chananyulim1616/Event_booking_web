<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, true, 'organizer');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTicket</title>
    <link rel="icon" type="image/x-icon" href="img/lillogo.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&family=Questrial&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>



    <link rel="stylesheet" href="richtexteditor/rte_theme_default.css" />
    <link rel="stylesheet" href='style2.css' />

    <script type="text/javascript" src="richtexteditor/rte.js"></script>
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <script type="text/javascript" src='richtexteditor/plugins/all_plugins.js'></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
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
    </script>
</head>

<body class='relative'>
    <div id="modal"></div>
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
                                <li>
                                    <a href="logout.php" class="flex justify-center items-center">
                                        <i class="fi fi-bs-exit px-2 text-red-600"></i>
                                        <p>ออกจากระบบ</p>
                                    </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:hidden flex flex-col z-30">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="flex inline-flex text-xl items-center  justify-between w-full text-white h-16 px-4 text-white  font-medium text-sm px-5 py-2.5 text-center  items-center "
                    type="button">
                    <div>
                        <i class="fi fi-rr-menu-burger"></i>
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
    <form method="POST" enctype="multipart/form-data" id='upload' action="index.php">
        <div class="min-w-full flex flex-col">
            <div style='background-color:#FAF3F0'
                class="flex w-full justify-center items-center backdrop-blur-md bg-cover bg-center p-3" id='backdrop'>
                <div style='padding-top:120px;padding-bottom:40px;'
                    class="flex h-full w-full justify-center items-center backdrop-blur-md">
                    <div style="box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);"
                        class="lg:w-[965.4px] lg:h-[514.95px] w-fit flex flex-col rounded-xl overflow-hidden lg:flex-row">
                        <div class="lg:w-2/3 lg:h-auto flex justify-center items-center "
                            style="background-color:#D8D9DA">
                            <label class="flex justify-center items-center h-full w-full bg-">
                                <img src="img/add.png" class="w-full" alt="" id='previewimg'>
                                <input type="file" name="photo" id="imageUpload" accept="image/" id='insertimg'
                                    onchange="previewImage(event)" style="display: none;" required />
                            </label>
                        </div>

                        <div class="flex justify-center items-center lg:p-14 p-8 w-full bg-white sm:text-lg text:base">
                            <div class='w-11/12 flex flex-col'>
                                <div class="w-full flex flex-col  gap-2">
                                    <strong>กรอกชื่อกิจกรรมของคุณ</strong>
                                    <span class="w-full">
                                        <input class=" border-solid border-2 rounded-lg w-full p-2" type="text"
                                            id="eventName" name="name" 0 required>
                                    </span>
                                </div>
                                <div class='pt-6 flex flex-col gap-4'>
                                    <div class='flex items-center  w-full gap-4'>
                                        <i class="fi fi-rr-marker pt-7 lg:text-5xl sm:text-4xl text-3xl" href="#"></i>
                                        <div class="flex flex-col w-full gap-2">
                                            <strong>สถานที่แสดง</strong>
                                            <textarea id="eventLocation" name='place' rows="2"
                                                class="block p-2.5 w-full rounded-lg border-2 border-solid"
                                                placeholder="อธิบายสถานที่" require></textarea>
                                        </div>
                                    </div>
                                    <div class='flex items-center  w-full gap-4'>
                                        <i class="fi fi-rr-shopping-cart pt-7 lg:text-5xl sm:text-4xl text-3xl w-fit"
                                            href="#"></i>
                                            <div class='flex flex-col gap-2 w-[82%] sm:w-auto'>
                                                <strong>วันเปิดจำหน่าย</strong>
                                                <input class="border-solid border-2 rounded-lg p-2"
                                                    type="datetime-local" id="eventOpenDate"
                                                    min="<?= date('Y-m-d\Th:i') ?>" name="sales_date" required>
                                        </div>
                                    </div>
                                    <div class='flex items-center w-full gap-4'>
                                        <i class="fi fi-rr-apps pt-7 lg:text-5xl sm:text-4xl text-3xl " href="#"></i>
                                        <div class='flex flex-col w-full gap-2'>
                                            <strong>ประเภทการแสดง</strong>
                                            <div class="Theme">
                                                <select class="border-solid border-2 rounded-lg p-2" id="eventType"
                                                    name="type" required>
                                                    <option value="คอนเสิร์ต">คอนเสิร์ต</option>
                                                    <option value="อีเว้นท์">อีเว้นท์</option>
                                                    <option value="นิทรรศการ">นิทรรศการ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center items-center flex-col p-6 gap-6">
                <div class='flex flex-col gap-6  xl:w-2/3 sm:w-4/5 w-full'>
                    <h1 class="lg:text-2xl text-xl font-bold">รายละเอียดกิจกรรม</h1>
                    <div class='richtexteditor'>
                        <div class="flex flex-col gap-4">
                            <form action="" method="post">
                                <input name="htmlcode" id="inp_htmlcode" type="hidden" />
                                <div id="div_editor1" class="richtexteditor"
                                    style="min-width: 0px; text-align: center;">
                                </div>

                                <script>
                                    var editor1 = new RichTextEditor(document.getElementById("div_editor1"));
                                    editor1.attachEvent("change", function () {
                                        document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
                                    });
                                </script>
                            </form>
                        </div>
                    </div>
                    <div class="">
                        <div class="flex flex-col gap-4" id="datetime-container">
                            <label class="lg:text-2xl text-xl font-bold" for="datetime">รอบการแสดง</label>
                            <div class='min-w-fit w-1/2 flex flex-col gap-4' id="datetime-container2">
                                <div class="flex items-center gap-4 pr-10">
                                    <input class="border-solid border-2 rounded-lg p-2 lg:text-lg text:base w-full " min="<?php
                                    $cu = new DateTime();
                                    echo $cu->format('Y-m-d\TH:i') ?>" type="datetime-local" id="datetime"
                                        name="datetime" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class='flex gap-4'>
                            <div class='rounded-xl py-2 px-6 ' style="background-color:#191D88;">
                                <button class="lg:text-lg text-md text-white font-bold" type="button"
                                    onclick="addNewDatetimeField()">เพิ่ม</button>
                            </div>

                        </div>
                    </div>
                    <div class="flex flex-col gap-4" id="ticket">
                        <label class="lg:text-2xl text-xl font-bold" for="datetime">บัตรเข้าชม</label>
                        <div class="flex flex-col gap-4" id='info'>
                            <div class='relative lg:text-lg text-base border-solid border-2 rounded-lg' id='block1'>
                                <form class='center px-6 py-8 m-0'>
                                    <div class='flex flex-col justify-center gap-4'>
                                        <div>
                                            <label for="zone">ชื่อบัตรเข้าชม :</label>
                                            <input class="border-solid border-2 rounded-lg p-2  w-full"
                                                type="text" name="zone" id="zone1" required>
                                        </div>
                                        <div>
                                            <label for="price">ราคา:</label>
                                            <input class="border-solid border-2 rounded-lg p-2  w-full"
                                                type="number" name="price" id="price1" required>
                                        </div>
                                        <div class='flex justify-around'>
                                            <div class='flex gap-2'>
                                                <input name='type_ticket1' id='type_ticket1_1' value='seat_opt'
                                                    type='radio'></input>
                                                <label>แบบที่นั่ง</label>
                                            </div>
                                            <div class='flex gap-2'>
                                                <input name='type_ticket1' id='type_ticket1_2' type='radio'
                                                    value='no_seat_opt'></input>
                                                <label>แบบไม่มีที่นั่ง</label>
                                            </div>
                                        </div>
                                        <div class='flex flex-col justify-center gap-4' id='info_for_create1'>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class='w-fit rounded-xl py-2 px-6' style="background-color:#191D88;" id='button'>
                            <button type="button" class="lg:text-lg text-md text-white font-bold"
                                onclick='create_zone()'>
                                สร้างบัตรเข้าชม
                            </button>
                        </div>
                    </div>
                    <div class='flex w-full justify-center'>
                        <div class='' id='bg_submit_data'>
                            <button id='submit_data'
                                class=" lg:text-lg text-md text-white font-bold disabled:cursor-default" type="button"
                                onclick='enter()'>
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <footer>
        <div class="bg-[#272829] flex w-full sm:flex-row flex-col sm:items-start items-center p-5 gap-5">
            <div class="sm:shrink-0">
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
    <script src='script.js'></script>
    <script src='dropdown_navorg.js'></script>

</body>

</html>