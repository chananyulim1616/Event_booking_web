<?php
require_once("dbconfig.php");
session_start();
$eid = $_GET['eventid'];
$id = $_SESSION['id'];
$login = checklogin($conn, true, 'organizer');
$sql = "SELECT tkc_name,price,width FROM ticket_class JOIN event using(event_id) 
WHERE event_id = $eid and org_id = $id ORDER BY price";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    header("location: index_org.php");
    exit();
}
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
where event_id = $eid  group by tkc_id order by price ;";
$result = mysqli_query($conn, $sql);
$zone_num = array();
while ($rows = mysqli_fetch_assoc($result)) {
    $zone_num[$rows['tkc_name']] = $rows['zone_num'];
}
$sql = "SELECT tkc_name, ticket_id, seat_num, invoice_id, seat_col FROM ticket join ticket_class using (tkc_id) 
where event_id = $eid and tkc_name = '$zone_select' and round_id=$round_select order by price ;";
$result = mysqli_query($conn, $sql);
$seats = array();
while ($rows = mysqli_fetch_assoc($result)) {
    array_push(
        $seats,
        array(
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

    
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }

        .dataTables_wrapper select,
        .dataTables_wrapper .dataTables_filter input {
            color: #4a5568;
            /*text-gray-700*/
            padding-left: 1rem;
            /*pl-4*/
            padding-right: 1rem;
            /*pl-4*/
            padding-top: .5rem;
            /*pl-2*/
            padding-bottom: .5rem;
            /*pl-2*/
            line-height: 1.25;
            /*leading-tight*/
            border-width: 2px;
            /*border-2*/
            border-radius: .25rem;
            border-color: #edf2f7;
            /*border-gray-200*/
            background-color: #edf2f7;
            /*bg-gray-200*/
        }

        /*Row Hover*/
        table.dataTable.hover tbody tr:hover,
        table.dataTable.display tbody tr:hover {
            background-color: #ebf4ff;
            /*bg-indigo-100*/
        }

        /*Pagination Buttons*/
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-weight: 700;
            /*font-bold*/
            border-radius: .25rem;
            /*rounded*/
            border: 1px solid transparent;
            /*border border-transparent*/
        }

        /*Pagination Buttons - Current selected */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: #fff !important;
            /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
            /*shadow*/
            font-weight: 700;
            /*font-bold*/
            border-radius: .25rem;
            /*rounded*/
            background: #667eea !important;
            /*bg-indigo-500*/
            border: 1px solid transparent;
            /*border border-transparent*/
        }

        /*Pagination Buttons - Hover */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: #fff !important;
            /*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
            /*shadow*/
            font-weight: 700;
            /*font-bold*/
            border-radius: .25rem;
            /*rounded*/
            background: #667eea !important;
            /*bg-indigo-500*/
            border: 1px solid transparent;
            /*border border-transparent*/
        }

        /*Add padding to bottom border */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0;
            /*border-b-1 border-gray-300*/
            margin-top: 0.75em;
            margin-bottom: 0.75em;
        }

        /*Change colour of responsive icon*/
        table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
            background-color: #667eea !important;
            /*bg-indigo-500*/
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
                            <ul class="py-2">
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
    <div class="min-w-full flex flex-col  xl:px-32 xl:pb-32 md:px-16 md:pb-16 px-8 pb-8 pt-32">
        <div class="flex justify-center  md:mx-28 lg:mt-26 lg:mx-28  mx-22  mb-3 gap-3 ">
            <label for="zone" class="text-2xl font-bold ">ประเภทบัตรบัตรเข้าชม:</label>
            <select id="zone"
                class="bg-gray-50 border border-gray-300  text-sm rounded-lg block  bg-gray-700 border-gray-600 
                placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
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
        <div class="flex justify-center mt-2 md:mx-28  lg:mx-28  mx-22 mb-3 gap-3 ">
            <label for="round" class="text-2xl font-bold ">รอบการแสดง:</label>
            <select id="round"
                class="bg-gray-50 border border-gray-300  text-sm rounded-lg block  bg-gray-700 border-gray-600 
                placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
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
                        echo "<div class='flex gap-2  items-center' id='row3'><p class='h-6 border-solid border-2 rounded-lg 
                        text-base w-8 text-center'>" . $col_name[$rows] . "</p></div>";
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
                                echo "id= '" . $seats[$count][1] . "' onclick='check(id)'></div>";
                                $count++;
                                if ($count >= $capacity) {
                                    break 2;
                                }
                            } else {
                                echo "<div class='noseat'></div>";
                            }
                        }
                        echo "</div>";}?>
                </div>
            </div>
        </div>
        </div>
        </div>
    <?php endif ?>
    <?php
    if ($zones[$zone_select][1] == 0): ?>
        <div id='result' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
            <table id="example" class="stripe hover text-center"
                style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                <thead>
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
                                <td >' . $count . '</td>
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
        <?php mysqli_close($conn); endif ?>

    <!-- footer -->
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

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!--Datatables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script>
        let count_table = 0;
        document.getElementById('example').setAttribute('id', 'example' + count_table)
        $(document).ready(function () {
            var table = $('#example' + count_table).DataTable({
                responsive: true
            })
                .columns.adjust()
                .responsive.recalc();
            count_table += 1
        });
    </script>
    <script>
        function retry() {
            var element = document.getElementById("pop");
            element.remove();
        }
        function check(id) {
            $.ajax({
                type: 'POST',
                url: 'query_vieworg.php',
                data: { id: id },
                success: function (response) {
                    modal = document.getElementById('modal')
                    modal.innerHTML = response
                },
                error: function () {
                    console.error('Error sending data to PHP');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#zone').change(function () {
                var selectedValueZone = $(this).val();
                var selectedValueRound = document.getElementById('round').value;
                updateSeatData(selectedValueZone, selectedValueRound);
            });
            $('#round').change(function () {
                var selectedValueZone = document.getElementById('zone').value;
                var selectedValueRound = $(this).val();
                updateSeatData(selectedValueZone, selectedValueRound);
            });
        });

        function updateSeatData(zoneSelect, roundSelect) {
            $.ajax({
                type: 'POST',
                url: 'create_table.php',
                data: {
                    zoneSelect: zoneSelect,
                    roundSelect: roundSelect,
                    e_id: '<?php echo $eid ?>'
                },
                success: function (result) {
                    $('div#result').replaceWith(result);
                    $('#zone').off('change').change(function () {
                        var selectedValueZone = $(this).val();
                        var selectedValueRound = document.getElementById('round').value;
                        updateSeatData(selectedValueZone, selectedValueRound);
                    });
                    $('#round').off('change').change(function () {
                        var selectedValueZone = document.getElementById('zone').value;
                        var selectedValueRound = $(this).val();
                        updateSeatData(selectedValueZone, selectedValueRound);
                    });

                    if (document.getElementById('seat-container') !== null) {
                        isEllipsisActive(document.getElementById('seat-container'))
                    }
                    
                    document.getElementById('example').setAttribute('id', 'example' + count_table)
                    $(document).ready(function () {
                        var table = $('#example' + count_table).DataTable({
                            responsive: true
                        })
                            .columns.adjust()
                            .responsive.recalc();
                        count_table += 1
                    });

                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error(textStatus); // Log any error messages
                }
            });
        }

        const isEllipsisActive = (e) => {
            if (e.offsetWidth >= e.scrollWidth) {
                e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2 justify-center'
            } else {
                e.classList = 'containercss overflow-x-auto overflow-y-hidden p-2'
            }
        }
    </script>
    <script src='dropdown_navorg.js'></script>
</body>

</html>