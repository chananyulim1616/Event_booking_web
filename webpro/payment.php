<?php

require_once("dbconfig.php");
$login = checklogin($conn, true, 'customer');
if (isset($_GET['zoneSelect'])) {
    $zone_select = $_GET["zoneSelect"];
    $seat_id = json_decode($_GET["seatID"]);
    $eventID = $_GET['event_id'];
    $total_seat = $_GET['total_seat'];
    $round = $_GET['round'];

    $sql = "SELECT sale_date,min(date_time) as start FROM event join round using (event_id) WHERE event_id = $eventID";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $now = new DateTime();
    $sale_date = new DateTime($result['sale_date']);
    $start_date = new DateTime($result['start']);
    if ($now > $start_date or $now < $sale_date) {
        header("Location: event.php?eventid=$eventID");
    }
    $sql = "SELECT COUNT(ticket_id) as max FROM ticket JOIN ticket_class using(tkc_id) where invoice_id IS NULL and round_id = $round and tkc_name = '$zone_select' and event_id = $eventID";
    $result = mysqli_query($conn, $sql);
    $max = mysqli_fetch_assoc($result)['max'];
    if ($max >= 6) {
        $max = 6;
    }
    if ($total_seat > $max) {
        die;
    }
    $seat_full = array();
    if (count($seat_id) == 0) {
        $sql = "SELECT ticket_id, seat_num as full FROM ticket JOIN ticket_class USING(tkc_id) WHERE invoice_id is NULL and event_id = $eventID 
        and tkc_name = '$zone_select' and round_id = $round LIMIT $total_seat";
        $result = mysqli_query($conn, $sql);
        $where = '';
        $count = 0;
        while ($value = mysqli_fetch_assoc($result)) {
            if ($count == $total_seat - 1) {
                $where = $where . $value['ticket_id'];
            } else {
                $where = $where . $value['ticket_id'] . ", ";
            }
            array_push($seat_full, $value['full']);
            array_push($seat_id, $value['ticket_id']);
            $count++;
        }

    } else {
        $sql = "SELECT ticket_id, CONCAT(seat_col,seat_num) as full FROM ticket JOIN ticket_class USING (tkc_id) WHERE event_id = $eventID and tkc_name ='$zone_select' and invoice_id IS NULL and ticket_id IN(";
        $count = 0;
        while ($count < $total_seat) {

            if ($count == 0) {
                $where = $seat_id[$count];
            } else {
                $where = "$where ," . $seat_id[$count];
            }
            $count++;
        }

        $sql = $sql . $where . ")";
        $result = mysqli_query($conn, $sql);
        while ($rows = mysqli_fetch_assoc($result)) {
            $zone_id = $rows['ticket_id'];
            array_push($seat_full, $rows['full']);
        }
    }
    if (mysqli_num_rows($result) == $total_seat) {
        $sql = "SELECT sum(price) FROM ticket JOIN ticket_class USING (tkc_id) WHERE event_id = $eventID and tkc_name = '$zone_select' and ticket_id IN(";
        $result = mysqli_query($conn, $sql . $where . ")");
        $price = mysqli_fetch_assoc($result)['sum(price)'];
        $sql = "SELECT date_time FROM round where round_id=$round";
        $result = mysqli_query($conn, $sql);
        $round_date = mysqli_fetch_assoc($result)['date_time'];

        $sql = "SELECT name FROM event WHERE event_id = $eventID";
        $result = mysqli_query($conn, $sql);

    } else {
        die;
    }

} else {
    die;
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
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
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
                        echo'<div class="relative inline-block text-left">
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
    <!-- ข้อมูล -->
    <div class="md:flex md:items-center items-left lg:px-60 sm:px-32 pt-32 pb-10">
        <p class="text-center text-3xl md:text-4xl  text-[#000000]"><b>
                <?= mysqli_fetch_assoc($result)['name'] ?>
            </b></p>
    </div>
    <div
        class="md:items-center max-sm:px-10 sm:max-lg:px-40 lg:px-72 w-full flex flex-col justify-center gap-10 pb-10 text-xl md:text-lg">
        <div class="w-full flex justify-between border-b-2 border-[#B4B4B3] ">
            <p class="font-bold">รอบการแสดง</p>
            <p class="">
                <?php echo $round_date ?>
            </p>
        </div>
        <div class="w-full flex justify-between border-b-2 border-[#B4B4B3]  ">
            <p class="font-bold">โซนที่นั่ง</p>
            <p class="">
                <?php echo $zone_select ?>
            </p>
        </div>
        <div class="w-full flex justify-between border-b-2 border-[#B4B4B3] ">
            <p class="font-bold">เลขที่นั่ง</p>
            <p class="">
                <?php echo implode(', ', $seat_full) ?>
            </p>
        </div>
        <div class="w-full flex justify-between border-b-2 border-[#B4B4B3] ">
            <p class="font-bold">จำนวนที่นั่ง</p>
            <p class="">
                <?php echo $total_seat ?>
            </p>
        </div>
        <div class="w-full flex justify-between border-b-2 border-[#B4B4B3] ">
            <p class="font-bold">ราคาทั้งหมด</p>
            <p class="">
                <?php echo $price . ' บาท' ?>
            </p>
        </div>
    </div>
    <!-- ชำระเงิน -->
    <div class="max-sm:px-10 sm:max-lg:px-40 lg:px-72 w-full">
        <p class="text-2xl text-[#000000]"><b>เลือกช่องทางชำระเงิน</b></p>
    </div>

    <div class=" max-sm:px-12 sm:max-lg:px-44 lg:px-80">
        <div class="flex text-xl pb-4 text-black gap-10">
            <div class="flex gap-2">
                <input type="radio" id="credit" name="payment-method" value="credit" required>
                <label for="credit">ชำระด้วยบัตรเครดิต</label>
            </div>
            <div class="flex gap-2">
                <input type="radio" id="bank" name="payment-method" value="bank" required>
                <label for="bank">ชำระผ่านธนาคาร</label>
            </div>
        </div>
        <div id='waypayment' class='flex flex-col gap-2'>
        </div>
    </div>

    <div class="flex justify-center items-center mt-20 mb-28 ">
        <div id='bg_submit_data'>
            <button class=" text-white font-bold text-xl h-14 " onclick='payment()' id='submit_data'>
                ยืนยันการชำระเงิน
            </button>
        </div>
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
    <script>
        let count = 0
        var role = document.querySelectorAll('[name="payment-method"]');
        var sale = document.getElementById('waypayment');

        role.forEach(e => {
            e.addEventListener('change', function () {
                if (e.value === 'credit') {
                    sale.innerHTML = `
            <div class="">
                <p class="text-xl text-[#000000]"><b>หมายเลขบัตรเครดิต</b></p>
            </div>

            <div class="">
                <div class="mt-2">
                    <input type="text" name="credit-card-number" id="credit-card-number"
                        autocomplete="credit-card-number" required
                        class="text-xl text-[#000000] block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="">
                <p class="text-xl text-[#000000]"><b>ชื่อบัตรเครดิต</b></p>
            </div>

            <div class="">
                <div class="mt-2">
                    <input type="text" name="credit-card-name" id="credit-card-name" autocomplete="credit-card-name" required
                        class="text-xl text-[#000000] block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
            </div>


            <div class="flex flex-col md:flex-row justify-start gap-4 md:gap-20 ">
                <div class="flex flex-col">
                    <p class="text-xl text-[#000000]"><b>เดือน</b></p>
                    <div class="mt-2">
                        <input type="text" name="month" id="month" placeholder="01" required
                            class="text-xl text-[#000000] block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex flex-col">
                    <p class="text-xl text-[#000000]"><b>ปี</b></p>
                    <div class="mt-2">
                        <input type="text" name="year" id="year" placeholder="2023" required
                            class="text-xl text-[#000000] block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex flex-col">
                    <p class="text-xl text-[#000000]"><b>CVV</b></p>
                    <div class="mt-2">
                        <input type="text" name="cvv" id="cvv" placeholder="CVV/CVV2" required
                            class="text-xl text-[#000000] block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>`
                    checkRequiredInputs();
                } else {
                    sale.innerHTML = `<div class="flex flex-col md:flex-row justify-start pb-4 gap-4">
                <div class="md:w-1/2">
                    <p class="text-xl text-[#000000]"><b>ธนาคารกสิกรไทย</b><br>บริษัทไอทิกเก็ตไทยแลนด์ จำกัด</p>
                </div>
                <div class="md:w-1/2">
                    <p class="text-xl text-[#000000]"><b>เลขบัญชี</b> 012-3-44444-5</p>
                </div>
            </div>`
                    checkRequiredInputs(); // Clear the content when "customer" is selected
                }
            });
        });
        console.log('<?= $_SESSION['id'] ?>')
        console.log('<?= $_SESSION['id'] ?>')

        const submitButton = document.getElementById('submit_data');
        const bgsubmitButton = document.getElementById('bg_submit_data');
        function checkRequiredInputs() {
            var requiredInputs = document.querySelectorAll('input[required]');
            let allInputsHaveValue = true;
            requiredInputs.forEach(input => {
                if (input.value.trim() === '') {
                    allInputsHaveValue = false;
                    console.log(input)
                }
            });
            if (count == 0) {
                allInputsHaveValue = false;
                count += 1
            }
            submitButton.disabled = !allInputsHaveValue;
            if (!allInputsHaveValue) {
                bgsubmitButton.classList = 'px-4 rounded-3xl bg-gray-600'
            } else {
                bgsubmitButton.classList = 'px-4 rounded-3xl bg-[#2BAF2B]'
            }
            // Attach the event listener to each required input
            requiredInputs.forEach(input => {
                input.addEventListener('input', checkRequiredInputs);
            });

        }
        checkRequiredInputs()
        function payment() {
            try {
                $.ajax({
                    type: 'POST',
                    url: 'payment_db.php',
                    data: {
                        zone_select: '<?= $zone_select ?>',
                        seat_id: '<?= json_encode($seat_id) ?>',
                        event_id: '<?= $eventID ?>',
                        round: <?= $round ?>
                    },
                    success: function (result) {
                        console.log(result)
                        window.location.replace('event.php?eventid=<?php echo $eventID ?>')
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.error(textStatus);
                    }
                });
            } catch (error) {
                console.error(error);
            }
        }
    </script>
    <script src="dropdown_navorg.js"></script>
</body>

</html>