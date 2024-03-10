<?php
require_once("dbconfig.php");
session_start();
$login = checklogin($conn, true, 'sales_marketing');
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
    <link rel='stylesheet'
        href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>


    <script src="https://cdn.tailwindcss.com"></script>
    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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


</head>

<body class="relative">
    <div id='all'>
        <div id='modal' class='z-50'></div>
        <nav class="fixed top-0 w-full z-20 px-4 py-3" style="background-color:#272829;">
            <div class="w-full mx-auto">
                <div class="hidden text-xl items-center md:flex justify-between w-full text-white h-16 px-4 ">
                    <div class=" h-full">
                        <img class="h-full" src="img/logo.png" alt="logo"></a>
                    </div>
                    <!-- icon user -->
                    <div class='flex items-center'>
                        <div class='flex items-center'>
                            <i class="fi fi-tr-house-chimney text-4xl  text-white"
                                onclick="window.location.href='index_sales.php';" style="cursor: pointer;"></i>
                        </div>
                        <div class="relative inline-block text-left ml-auto">
                            <button id="userLink2" data-dropdown-toggle="dropdownDefaultButton2"
                                class="flex inline-flex items-center justify-between w-full font-medium text-sm px-5 py-2.5 text-center rounded-lg focus:outline-none focus:shadow-outline">
                                <div class="">
                                    <i class="fi fi-tr-circle-user text-4xl" style="color:white;"></i>
                                </div>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdown2" class="hidden absolute right-0 mt-2 w-44 bg-white text-black text-lg rounded-lg shadow-lg
                             border-2 border-gray-300 divide-y divide-gray-400">
                                <ul class="py-2 ">
                                    <li>
                                        <a href="logout.php" class="flex justify-center items-center">
                                            <i class="fi fi-bs-exit pt-2 px-2 text-red-600"></i>
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
                                <a href="#" class="block  px-4 py-2 ">หน้าหลัก</a>
                            </li>
                        </ul>
                        <div class="py-2 text-md bg-white" style="color:#191D88"
                            aria-labelledby="dropdownDefaultButton">
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
            <p class='sm:text-3xl text-center text-xl' style="color: #272829;">"Discover, Experience, Celebrate - Your
                Event Hub!"</p>
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
                $sql = 'SELECT org_id from sales_marketing where sales_id = ' . $_SESSION['id'];
                ;
                $result = mysqli_fetch_assoc(mysqli_query($conn, $sql))['org_id'];
                //$sql = 'SELECT event_id,name, poster from event where org_id = ' . $result;
                $sql = 'SELECT event_id,name, poster from event where org_id = ' . $result;
                $result = mysqli_query($conn, $sql);
                echo "<div class='z-0 grid 2xl:grid-cols-4 xl:grid-cols-3 md:grid-cols-2 grid-cols-1  justify-items-center gap-y-11 2xl:gap-y-9 w-full'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $eid = $row['event_id'];
                    $pos = $row['poster'];
                    $na = $row['name'];
                    $id = $_SESSION['id'];
                    echo "<div id='$eid' style='background-color:#FAF3F0; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);'
                    class='event transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-105 duration-300 gap-y-2 p-8 rounded-xl w-80 
                    2xl:w-72 text-xs md:text-md  xl:text-lg flex justify-between items-center flex-col pb-5'>
                        <img class=' rounded-lg mb-2' style='box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);' src='$pos'></img>
                        <div class='w-full md:text-xl text-lg font-semibold h-16 flex justify-center items-center'>
                            <div class='text-center h-full line-clamp-2 ' id='p_name'>$na</div>
                        </div>
                </div>";
                }
                ;
                ?>
            </div>
        </div>
    </div>

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
    <script>
        function retry(event) {
            if (event.target === document.getElementById('pop')) {
                var element = document.getElementById("pop");
                element.remove();
            }

        }
        function user() {
            var modal = document.getElementById('modal')
            modal.innerHTML +=
                `<div id ="pop" class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="fixed inset-0 z-50 w-screen overflow-y-auto" onclick="retry(event)">
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

        event = document.querySelectorAll('.event')
        event.forEach(element => {
            element.addEventListener('click', function () {
                $.ajax({
                    type: 'POST',
                    url: 'stat_edit.php',
                    data: {
                        eid: element.id
                    },
                    success: function (result) {
                        document.getElementById('modal').innerHTML = result;
                        var table = $('#example').DataTable({ responsive: true }).columns.adjust().responsive.recalc();

                        const closeModalButton = document.getElementById('closeModalButton');
                        const pop = document.getElementById('pop');
                        closeModalButton.addEventListener('click', () => {
                            pop.classList.add('hidden');
                        });

                        // Nested AJAX request
                        $.ajax({
                            type: 'POST',
                            url: 'create_data.php',
                            data: {
                                eid: element.id,
                            },
                            success: function (data) {
                                data = JSON.parse(data)
                                let tkcd1 = document.getElementById('ticket_type_drop');
                                let dtd1 = document.getElementById('datetime_drop');
                                const emptyChartData = {
                                    labels: ['บัตรเข้าชมที่ขายได้', 'บัตรเข้าชมที่ยังขายไม่ได้'],
                                    datasets: [{
                                        label: 'จำนวน',
                                        backgroundColor: [
                                            'rgba(63, 81, 181)', 'rgba(233, 30, 99)'
                                        ],
                                        data: data[''],
                                        borderWidth: 2,
                                        hoverOffset: 8,

                                    }]
                                };
                                const ctx = document.getElementById('myChart');
                                let mychart = new Chart(ctx, {
                                    type: 'pie',
                                    data: emptyChartData,
                                    options: {
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                labels: {
                                                    color: '#fff',
                                                    font: {
                                                        family: 'IBM Plex Sans Thai',
                                                        size: 16
                                    }}}}}});

                                function updateChartAndTable(tkcd, dtd) {
                                    mychart.data.datasets[0].data = data[tkcd + dtd];
                                    mychart.update();
                                    $.ajax({
                                        type: 'POST',
                                        url: 'create_popstat.php',
                                        data: {
                                            eid: element.id,
                                            type: tkcd,
                                            datetime: dtd
                                        },
                                        success: function (result) {
                                            document.getElementById('result').innerHTML = result;
                                            var table = $('#example').DataTable({ responsive: true }).columns.adjust().responsive.recalc();
                                        },
                                        error: function (xhr, textStatus, errorThrown) {
                                            console.error(textStatus);
                                            console.error(errorThrown);
                                        }
                                    });
                                }
                                tkcd1.onchange = function () {
                                    var tkcd = tkcd1.value;
                                    var dtd = dtd1.value;
                                    updateChartAndTable(tkcd, dtd);
                                }
                                dtd1.onchange = function () {
                                    var tkcd = tkcd1.value;
                                    var dtd = dtd1.value;
                                    updateChartAndTable(tkcd, dtd);
                                }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                console.error(textStatus);
                                console.error(errorThrown);
                            }
                        });
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.error(textStatus);
                        console.error(errorThrown);
                    }
                });
            });
        });



    </script>
    <script src='dropdown_navorg.js'></script>
</body>

</html>