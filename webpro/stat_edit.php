<!-- <script src="https://cdn.tailwindcss.com"></script>
<script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
require_once 'dbconfig.php';
session_start();

$sql = "SELECT org_id FROM sales_marketing WHERE sales_id = " . $_SESSION['id'];
$result = mysqli_query($conn, $sql);
$orgid = mysqli_fetch_assoc($result)['org_id'];

$eid = $_POST['eid'];


$sql = "SELECT SUM(total_price) as total,SUM(total_tickets) as total_ticket  FROM invoice 
JOIN ticket_class USING(tkc_id) JOIN event USING(event_id)
WHERE event_id = $eid AND org_id = $orgid ;";

$total = mysqli_fetch_assoc(mysqli_query($conn, $sql))['total'];
$total_ticket = mysqli_fetch_assoc(mysqli_query($conn, $sql))['total_ticket'];

$sql = "SELECT
DISTINCT(tc.tkc_name) as 'name'
FROM
ticket t
LEFT JOIN ticket_class tc ON t.tkc_id = tc.tkc_id
LEFT JOIN event e ON tc.event_id = e.event_id
WHERE
e.event_id = $eid
AND e.org_id = $orgid ;";

$tkc_class = array();
$result = mysqli_query($conn, $sql);
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($tkc_class, $rows['name']);
};
$sql = "SELECT
DISTINCT(r.date_time) as 'name'
FROM
ticket t
LEFT JOIN round r ON r.round_id = t.round_id
LEFT JOIN ticket_class tc ON t.tkc_id = tc.tkc_id
LEFT JOIN event e ON tc.event_id = e.event_id
WHERE
e.event_id = $eid
AND e.org_id = $orgid
AND r.date_time IS NOT NULL;";

$round_date = array();
$result = mysqli_query($conn, $sql);
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($round_date, $rows['name']);
}
;

//prepare table
$sql = "SELECT
    DISTINCT(i.invoice_id) as invoice_id,
    i.status,
    CONCAT(c.fname, ' ', c.lname) AS fullname,
    i.buy_time,
    i.total_price,
    tc.tkc_name,
    r.date_time,
    i.total_tickets
FROM
    invoice i
    LEFT JOIN ticket_class tc ON i.tkc_id = tc.tkc_id
    LEFT JOIN ticket t ON i.invoice_id = t.invoice_id
    LEFT JOIN round r ON t.round_id = r.round_id
    LEFT JOIN customer c ON i.user_id = c.user_id
    LEFT JOIN event e ON r.event_id = e.event_id
WHERE
    e.event_id = $eid
    AND e.org_id = $orgid
ORDER BY r.date_time ;";
$result = mysqli_query($conn, $sql);

function dateTimeToWords($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('j F  Y, เวลา g:i A');
}
echo '
<!-- Modal Overlay -->
<div id="pop" onclick="retry(event)"
    class="z-40 w-screen h-screen fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <!-- Modal Container -->
    <div class="z-50 bg-[#272829] w-fit max-w-[90%] rounded-lg p-8 max-h-[83%] overflow-y-auto text-white">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl md:text-xl font-bold">ข้อมูลยอดขาย</h2>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Modal Card -->
        <div class="flex gap-12 justify-items-stretch items-center my-6 xl:flex-row flex-col-reverse">
            <!-- card -->
            <div class="flex md:flex-row flex-col gap-12 items-center xl:justify-normal justify-around xl:w-auto w-full">
                <div class="xl:text-left text-center block max-w-sm p-3 bg-white border border-gray-200 rounded-lg shadow xl:w-auto w-full"
                style="background-color:#272829; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);">
                    <div class="flex flex-col">
                        <p class=" md:text-xl text-lg font-semibold tracking-tight">รายได้ทั้งหมด</p>
                        <p class=" md:text-md text-sm text-gray-200 ">    ' . $total . ' บาท</p>
                    </div>
                </div>
                <div class="xl:text-left text-center block max-w-sm p-3 bg-white border border-gray-200 rounded-lg shadow xl:w-auto w-full"
                style="background-color:#272829; box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);">
                    <div class="flex flex-col">
                        <p class=" md:text-xl text-lg font-semibold tracking-tight   ">ยอดขายบัตรเข้าชม</p>
                        <p class=" md:text-md text-sm text-gray-200 ">    ' . $total_ticket . ' ใบ</p>
                    </div>
                </div>
            </div>' ?>

<div class='flex md:flex-row flex-col gap-12 items-center xl:justify-normal justify-around  md:text-md text-sm'>
    <div class="flex sm:flex-row flex-col justify-center  gap-3 items-center ">
        <label for="" class="md:text-md text-sm font-bold">ประเภทบัตรเข้าชม:</label>
        <select id="ticket_type_drop"
            class="p-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  ">
            <option value='' selected>ทั้งหมด</option>
            <?php
            foreach ($tkc_class as $name) {
                echo "<option value='$name'>$name</option>";
            }
            ; ?>
        </select>
    </div>
    <div class="flex sm:flex-row flex-col justify-center  gap-3 items-center ">
        <label for="" class="md:text-md text-sm font-bold  ">รอบการแสดง:</label>
        <select id="datetime_drop"
            class="p-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block  ">
            <option value='' selected>ทั้งหมด</option>
            <?php
            foreach ($round_date as $name) {
                echo "<option value='$name'>" . dateTimeToWords($name) . "</option>";
            }
            ;
            ?>
        </select>
    </div>
</div>
</div>
<!-- Modal Content -->
<div class="flex lg:flex-row flex-col-reverse lg:gap-0 gap-5">

    <!-- table -->
    <div id="result" class="p-5 px-6 rounded shadow bg-white md:text-md text-sm lg:w-3/4 xl:flex-1 text-[#333] overflow-x-auto">
        <table id="example" class="stripe hover  text-center "
            style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
            <thead>
                <tr>
                    <th data-priority="1">รหัสใบเสร็จ</th>
                    <th data-priority="2">สถานะ</th>
                    <th data-priority="3">ชื่อผู้ซื้อ</th>
                    <th data-priority="4">ประเภทบัตรเข้าชม</th>
                    <th data-priority="5">รอบการแสดง</th>
                    <th data-priority="6">ราคาทั้งหมด</th>
                    <th data-priority="7">จำนวนบัตรเข้าชม</th>
                    <th data-priority="8">เวลาที่ซื้อ</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $count = 1;
                while ($rowTable = mysqli_fetch_assoc($result)) {
                    echo '<tr class="">
                <td class="whitespace-nowrap" >' . $rowTable['invoice_id'] . '</td>
                <td class="whitespace-nowrap" >' . $rowTable['status'] . '</td>
                <td class="whitespace-nowrap" >' . $rowTable['fullname'] . '</td>
                <td class="whitespace-nowrap" >' . $rowTable['tkc_name'] . '</td>
                <td class="whitespace-normal" >' . dateTimeToWords($rowTable['date_time']) . '</td>
                <td class="whitespace-nowrap" >' . $rowTable['total_price'] . '</td>
                <td class="whitespace-nowrap" >' . $rowTable['total_tickets'] . '</td>
                <td class="whitespace-normal" >' . dateTimeToWords($rowTable['buy_time']) . '</td>

            </tr>';
                    $count += 1;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="min-w-fit">
        <div class="mx-auto min-w-64 w-fit  overflow-hidden">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
</div>
</div>
</div>