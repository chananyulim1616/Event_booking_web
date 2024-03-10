<?php
require_once 'dbconfig.php';
session_start();
$sql = "SELECT org_id FROM sales_marketing WHERE sales_id = " . $_SESSION['id'];
$result = mysqli_query($conn, $sql);
$orgid = mysqli_fetch_assoc($result)['org_id'];

$eid = $_POST['eid'];
$type = $_POST['type'];
$datetime = $_POST['datetime'];

if ($datetime == '' and $type == '') {
    $sql = "SELECT
    DISTINCT(i.invoice_id) as invoice_id,
    i.status,
    CONCAT(c.fname, ' ', c.lname) AS fullname,
    i.buy_time,
    i.total_price,
    i.total_tickets,
    tc.tkc_name,
    r.date_time
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
} else if ($datetime == '' and $type != '') {
    $sql = "SELECT
    DISTINCT(i.invoice_id) as invoice_id,
    i.status,
    CONCAT(c.fname, ' ', c.lname) AS fullname,
    i.buy_time,
    i.total_price,
    i.total_tickets,
    tc.tkc_name,
    r.date_time
FROM
    invoice i
    LEFT JOIN ticket_class tc ON i.tkc_id = tc.tkc_id
    LEFT JOIN ticket t ON i.invoice_id = t.invoice_id
    LEFT JOIN round r ON t.round_id = r.round_id
    LEFT JOIN customer c ON i.user_id = c.user_id
    LEFT JOIN event e ON r.event_id = e.event_id
WHERE
    e.event_id = $eid
    AND e.org_id = $orgid AND tc.tkc_name = '$type'
ORDER BY r.date_time ;";
    $result = mysqli_query($conn, $sql);
} else if ($datetime != '' and $type == '') {
    $sql = "SELECT
    DISTINCT(i.invoice_id) as invoice_id,
    i.status,
    CONCAT(c.fname, ' ', c.lname) AS fullname,
    i.buy_time,
    i.total_price,
    i.total_tickets,
    tc.tkc_name,
    r.date_time
FROM
    invoice i
    LEFT JOIN ticket_class tc ON i.tkc_id = tc.tkc_id
    LEFT JOIN ticket t ON i.invoice_id = t.invoice_id
    LEFT JOIN round r ON t.round_id = r.round_id
    LEFT JOIN customer c ON i.user_id = c.user_id
    LEFT JOIN event e ON r.event_id = e.event_id
WHERE
    e.event_id = $eid
    AND e.org_id = $orgid  AND r.date_time = '$datetime'
ORDER BY r.date_time ;";
    $result = mysqli_query($conn, $sql);
} else {
    $sql = "SELECT
    DISTINCT(i.invoice_id) as invoice_id,
    i.status,
    CONCAT(c.fname, ' ', c.lname) AS fullname,
    i.buy_time,
    i.total_price,
    i.total_tickets,
    tc.tkc_name,
    r.date_time
FROM
    invoice i
    LEFT JOIN ticket_class tc ON i.tkc_id = tc.tkc_id
    LEFT JOIN ticket t ON i.invoice_id = t.invoice_id
    LEFT JOIN round r ON t.round_id = r.round_id
    LEFT JOIN customer c ON i.user_id = c.user_id
    LEFT JOIN event e ON r.event_id = e.event_id
WHERE
    e.event_id = $eid
    AND e.org_id = $orgid  AND r.date_time = '$datetime' AND tc.tkc_name = '$type'
ORDER BY r.date_time ;";
    $result = mysqli_query($conn, $sql);
}
function dateTimeToWords($dateString)
{
    $date = new DateTime($dateString);
    return $date->format('j F  Y, เวลา g:i A');
} ?>
<table id="example" class="stripe hover text-center " style="width:100%; 
padding-top: 1em;  padding-bottom: 1em;">
    <thead>
        <tr>
            <th data-priority="1">รหัสใบเสร็จ</th>
            <th data-priority="2">สถานะ</th>
            <th data-priority="3">ชื่อผู้ซื้อ</th>
            <th data-priority="4">ประเภทบัตรเข้าชม</th>
            <th data-priority="5">รอบแสดง</th>
            <th data-priority="6">ราคาทั้งหมด</th>
            <th data-priority="7">จำนวนบัตรเข้าชม</th>
            <th data-priority="8">เวลาที่ซื้อ</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $count = 1;
        while ($rowTable = mysqli_fetch_assoc($result)) {
            echo '<tr>
                <td>' . $rowTable['invoice_id'] . '</td>
                <td>' . $rowTable['status'] . '</td>
                <td>' . $rowTable['fullname'] . '</td>
                <td>' . $rowTable['tkc_name'] . '</td>
                <td>' . $rowTable['date_time'] . '</td>
                <td>' . $rowTable['total_price'] . '</td>
                <td>' . $rowTable['total_tickets'] . '</td>
                <td>' . $rowTable['buy_time'] . '</td>

            </tr>';
            $count += 1;
        }
        ?>
    </tbody>
</table>