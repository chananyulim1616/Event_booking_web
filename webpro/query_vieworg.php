<?php
include 'dbconfig.php';
session_start();

// Validate and sanitize user input
$id = $_POST['id'];
$sql = "SELECT CONCAT(seat_col,seat_num) as seat_full, date_time,CONCAT(fname,' ',lname) as fullname 
FROM ticket JOIN round USING(round_id) LEFT JOIN invoice USING (invoice_id) LEFT JOIN customer 
USING (user_id) WHERE ticket_id = $id;";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row['fullname'] == '') {
    $fn = '-';
} else {
    $fn = $row['fullname'];
}
function dateTimeToWords($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('j F  Y, เวลา g:i A');
}
echo '<div id="pop" class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
<div class="fixed inset-0 z-50 w-screen overflow-y-auto" onclick="retry()">
<div class="flex justify-center items-center h-full w-full inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex w-fit min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-8 pt-8">
                <div class="sm:items-center">

                    <h3 class="text-center font-bold text-xl  text-gray-900"
                        id="modal-title">
                        ข้อมูลบัตรเข้าชม</h3>
                    <div class="font-medium mt-5">
                        <div class="flex  gap-x-3">
                            <p>รหัส: </p>
                            <p>' . $id . '</p>
                        </div>
                        <div class="flex  gap-x-3">
                            <p>ชื่อ: </p>
                            <p>' . $fn . '</p>
                        </div>
                        <div class="flex  gap-x-3">
                            <p>รอบการแสดง: </p>
                            <p>' . dateTimeToWords($row['date_time']) . '</p>
                        </div>
                        <div class="flex  gap-x-3">
                            <p>ที่นั่ง: </p>
                            <p>' . $row['seat_full'] . '</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 pt-3 pb-4 sm:flex justify-center sm:px-6">
                <button type="button" onclick="retry()"s
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm 
                    ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">ตกลง</button>
            </div>
            </div>
        </div>
    </div>
</div>
</div>';