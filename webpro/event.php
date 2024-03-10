<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, false, 'customer');
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css'>

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css'>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel="stylesheet" href="richtexteditor/rte_theme_default.css" />
    <link rel="stylesheet" href='style2.css' />
    <script type="text/javascript" src="richtexteditor/rte.js"></script>
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <script type="text/javascript" src='richtexteditor/plugins/all_plugins.js'></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }

        ul,
        ol {
            padding: 0 1rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            line-height: 1.1;
        }

        img {
            height: auto;
            min-height: 100%;
        }

        blockquote {
            padding-left: 1rem;
            border-left: 2px solid rgba(#0D0D0D, 0.1);
        }

        hr {
            display: block;
            unicode-bidi: isolate;
            margin-block-start: 0.5em;
            margin-block-end: 0.5em;
            margin-inline-start: auto;
            margin-inline-end: auto;
            overflow: hidden;
            border-style: inset;
            border-width: 1px;
        }
    </style>

    <link rel="stylesheet" href="richtext_event.css">

</head>
ิ
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
                                <div class="bg-white ">
                                <a class ='flex gap-3 items-center' href='register.php'>
                                <i class="fi fi-rs-ticket  text-red-600"></i>
                                <p>ประวัติการจอง</p>
                                </a>

                                <a class ='flex gap-3 items-center mt-1' href='logout.php'>
                                <i class="fi fi-bs-exit  text-red-600"></i>
                                <p>ออกจากระบบ</p>
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
                                        <i class="fi fi-tr-circle-user text-4xl "></i>
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
                                    class="block px-4 py-2">เข้าสู่ระบบ/สมัครสมาชิก</a>';
                        } else {
                            echo '<div id = "user" onclick="user()"
                                    class="block px-4 py-2  flex items-center gap-3">
                                    <i class="fi fi-tr-circle-user text-3xl " style="color: #191D88;"></i>
                                    <p>' . $_SESSION["user_username"] . '</p>
                                    </div>';
                        }
                        ?>

                    </div>

                </div>
            </div>
        </div>
    </nav>

    <?php
    // เชื่อมต่อกับฐานข้อมูล MySQL
    // รับ event_id จาก URL
    if (isset($_GET['eventid'])) {
        $event_id = $_GET['eventid'];
        function dateTimeToWords($dateString)
        {
            $date = new DateTime($dateString);
            return $date->format('j F  Y, เวลา g:i A');
        }

        // สร้างคำสั่ง SQL เพื่อค้นหาข้อมูลจากตาราง events
        $sql = "SELECT poster, name, place, sale_date, type, description, count(date_time) as total , min(date_time) as start, max(date_time) as end 
        FROM event
        join round using (event_id) 
        WHERE event_id = $event_id";

        // ส่งคำสั่ง SQL ไปยังฐานข้อมูล
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
            echo '
    <div >
        <div class="min-w-full flex flex-col">
            <div class="flex w-full justify-center items-center backdrop-blur-md bg-cover  bg-center bg-[url(' . $row['poster'] . ')]"
            id="backdrop">
                <div style="padding-top:120px;padding-bottom:40px;" 
                class="flex h-full w-full justify-center items-center backdrop-blur-md p-3">
                    <div style="box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);" 
                    class="lg:w-[965.4px] lg:h-[514.95px] sm:w-3/4 w-11/12 h-auto flex rounded-xl overflow-hidden flex-col lg:flex-row">
                        <div class="lg:w-2/3 w-full  flex justify-center items-center" style="background-color:#D8D9DA">
                            <img class="w-full" src="' . $row['poster'] . '" alt=""  class="w-full">  
                        </div>

                        <div class="flex justify-center items-center lg:p-14 p-8 w-full bg-white opacity-80 text-xl ">
                            <div class="w-11/12 flex flex-col">
                                <div class="w-full flex flex-col justify-center items-center gap-2">
                                    <div class=" text-4xl font-bold text-center">
                                    ' . $row['name'] . ' </div>
                                </div>
                                <div class="pt-6 flex flex-col gap-4">
                                    <div class="flex items-center justify-center w-full gap-4">
                                        <i class="fi fi-rr-calendar  text-4xl " href="#"></i>
                                        <div class="flex flex-col w-full gap-2">
                                            <strong>วันที่จัดแสดง</strong>
                                             <p class="">';
            if ($row['total'] > 1) {
                echo dateTimeToWords($row['start']) . ' ถึง ' . dateTimeToWords($row['end']);
            } else {
                echo dateTimeToWords($row['start']);
            }
            echo '
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center w-full gap-4">
                                        <i class="fi fi-rr-marker  text-4xl" href="#"></i>
                                        <div class="flex flex-col w-full gap-2">
                                            <strong>สถานที่แสดง</strong>
                                            <p class="">
                                            ' . $row['place'] . '</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center w-full gap-4">
                                        <i class="fi fi-rr-shopping-cart  text-4xl " href="#"></i>
                                        <div class="flex flex-col w-full gap-2">
                                            <strong>วันเปิดจำหน่าย</strong>
                                            <div class=" ">' . dateTimeToWords($row['sale_date']) . '</div>
                                    </div>
                                </div>
                                <div class="flex items-center w-full gap-4">
                                    <i class="fi fi-rr-apps  text-4xl" href="#"></i>
                                    <div class="flex flex-col w-full gap-2">
                                        <strong>ประเภทการแสดง</strong>
                                        <div class="Theme">
                                            <p class="">' . $row['type'] . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div id="frame"  class="w-full flex justify-center items-center flex-col pt-8 gap-6">
        <div class="flex flex-col gap-6 w-full xl:w-2/3 sm:w-4/5 w-full px-4">
                
            <div id="des" class="sm:text-lg text-base ">
                ' . $row['description'] . '
            </div>
        </div>
    </div>';
            $now = new DateTime();
            $sale_date = new DateTime($row['sale_date']);
            $start_date = new DateTime($row['start']);
            if ($now > $start_date) {
                echo '
    <a  class ="w-full flex justify-center items-center my-16">
        <button disable
            class="bg-gray-600 text-2xl text-white font-bold py-4 px-24 rounded-full cursor-default ">
            ซื้อบัตร
        </button>
    </a>';
            } elseif ($now > $sale_date) {
                echo '
    <a href ="booking.php?eventid=' . $event_id . '" class ="w-full flex justify-center items-center my-16">
        <button
            class="bg-[#191D88] hover:bg-[#1B1A17] text-2xl text-white font-bold py-4 px-24 rounded-full ">
            ซื้อบัตร
        </button>
    </a>';

            } else {
                echo '
    <a  class ="w-full flex justify-center items-center my-16">
        <button disable
            class="bg-gray-600 text-2xl text-white font-bold py-4 px-24 rounded-full cursor-default ">
            ซื้อบัตร
        </button>
    </a>';
            }
            echo '
           </div>

       </div>
       </div>
       </form>
       <footer>
       <div class="bg-[#191D88] flex w-full sm:flex-row flex-col  sm:items-start items-center p-5 gap-5">
           <div class=" sm:shrink-0">
               <img src="img/logo.png" alt="logo">
           </div>
           <div class="flex sm:text-left text-center">
               <p class="text-white">หากคุณมีข้อสงสัยหรือติดปัญหาเกี่ยวกับการใช้งานระบบ สามารถติดต่อ
                   ทุกวันจันทร์-อาทิตย์ 10:00 – 18:00 น. <br>
                   อีเมล : iticketthailand@gmail.com <br>
                   เบอร์โทรศัพท์ : 061-123-4567 </p>
           </div>
       </div>
   </footer>
   ';
        }
        // ปิดการเชื่อมต่อกับฐานข้อมูล
        mysqli_close($conn);

    }
    ?>
    <script>

        const img = document.querySelectorAll('#des img')
        img.forEach(element => {
            element.style.maxHeight = element.style.height
            element.style.height = 'auto'
        });


        const elements = document.querySelector('#des').querySelectorAll('[style*="text-align: center"]')
        elements.forEach(element => {
            element = document.querySelectorAll('img')
            element.forEach(img => {
                img.classList = 'inline-block'
            });
        });
    </script>
    <script src='dropdown_navorg.js'></script>
</body>

</html>