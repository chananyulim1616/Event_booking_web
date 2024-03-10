<?php
require_once 'dbconfig.php';
session_start();

$sql = "SELECT org_id FROM sales_marketing WHERE sales_id = ".$_SESSION['id'];
$result = mysqli_query($conn, $sql); 
$orgid = mysqli_fetch_assoc($result)['org_id'];

$eid = $_POST['eid'];

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
AND e.org_id = $orgid;";

$round_date = array();
$result = mysqli_query($conn, $sql);
while ($rows = mysqli_fetch_assoc($result)) {
    array_push($round_date, $rows['name']);
};

$data = array();
// prepare data for chart type
$sql = "SELECT tc.tkc_id, tc.tkc_name, COALESCE(COUNT(CASE WHEN t.invoice_id IS NOT NULL THEN t.ticket_id END), 0) AS ticket_count FROM ticket_class tc 
LEFT JOIN ticket t ON tc.tkc_id = t.tkc_id WHERE tc.event_id = $eid GROUP BY tc.tkc_id, tc.tkc_name ORDER BY tkc_id;";
$result = mysqli_query($conn, $sql); 
$sql2 = "SELECT tkc_id, tkc_name, COUNT(CASE WHEN invoice_id IS NULL THEN 1 END) AS total FROM ticket JOIN ticket_class USING(tkc_id) WHERE event_id = $eid GROUP BY tkc_id, tkc_name;";
$result2 = mysqli_query($conn, $sql2); 


while ($row = mysqli_fetch_assoc($result)) {
    $tkc_name = $row['tkc_name'];
    $data[$tkc_name] = array(+$row['ticket_count'], 0);
}

while ($row2 = mysqli_fetch_assoc($result2)) {
    $tkc_name = $row2['tkc_name'];
    if (isset($data[$tkc_name])) {
        $data[$tkc_name][1] = $row2['total'];
    }
}

// prepare data for chart all
$sql = "select count(*) as ticket_count from ticket JOIN ticket_class USING(tkc_id) where event_id = $eid and invoice_id is not null;";
$result = mysqli_query($conn, $sql); //buy
$sql2 = "select count(*) as total from ticket JOIN ticket_class USING(tkc_id) where event_id = $eid and invoice_id is null;";
$result2 = mysqli_query($conn, $sql2); //all

while ($row = mysqli_fetch_assoc($result)) {
    $data[''] = array(+$row['ticket_count'], 0);
}

while ($row2 = mysqli_fetch_assoc($result2)) {
    if (isset($data[$tkc_name])) {
        $data[''][1] = +$row2['total'];
    }
}
// prepare data for chart round & type
$sql = "SELECT tc.tkc_id, tc.tkc_name,r.date_time, COALESCE(COUNT(CASE WHEN t.invoice_id IS NOT NULL THEN t.ticket_id END), 0) AS ticket_count FROM ticket_class tc 
LEFT JOIN ticket t ON tc.tkc_id = t.tkc_id
LEFT JOIN round r ON r.round_id = t.round_id WHERE tc.event_id = $eid
GROUP BY tc.tkc_id, tc.tkc_name,t.round_id ORDER BY tc.tkc_id, t.round_id;";
$result = mysqli_query($conn, $sql);

$sql2 = "SELECT tc.tkc_id, tc.tkc_name,r.date_time, COALESCE(COUNT(CASE WHEN t.invoice_id IS NULL THEN t.ticket_id END), 0) AS ticket_count FROM ticket_class tc 
LEFT JOIN ticket t ON tc.tkc_id = t.tkc_id
LEFT JOIN round r ON r.round_id = t.round_id WHERE tc.event_id = $eid
GROUP BY tc.tkc_id, tc.tkc_name,t.round_id ORDER BY tc.tkc_id, t.round_id;";
$result2 = mysqli_query($conn, $sql2); //all

while ($row = mysqli_fetch_assoc($result)) {
    $tkc_name = $row['tkc_name'];
    $date_name = $row['date_time'];
    $data[$tkc_name . $date_name] = array(+$row['ticket_count'], 0);
}

while ($row2 = mysqli_fetch_assoc($result2)) {
    $tkc_name = $row2['tkc_name'];
    $date_name = $row2['date_time'];
    $data[$tkc_name . $date_name][1] = +$row2['ticket_count'];
}
// prepare data for chart round
$sql = "SELECT r.date_time, COALESCE(COUNT(CASE WHEN t.invoice_id IS NOT NULL THEN t.ticket_id END), 0) AS ticket_count FROM ticket_class tc LEFT JOIN ticket t 
ON tc.tkc_id = t.tkc_id LEFT JOIN round r ON r.round_id = t.round_id WHERE tc.event_id = $eid and r.date_time is not null GROUP BY t.round_id ORDER BY t.round_id;";
$result = mysqli_query($conn, $sql); //buy
$sql2 = "SELECT r.date_time, COALESCE(COUNT(CASE WHEN t.invoice_id IS NULL THEN t.ticket_id END), 0) AS ticket_count FROM ticket_class tc LEFT JOIN ticket t
 ON tc.tkc_id = t.tkc_id LEFT JOIN round r ON r.round_id = t.round_id WHERE tc.event_id = $eid and r.date_time is not null GROUP BY t.round_id ORDER BY t.round_id;";
$result2 = mysqli_query($conn, $sql2); //all

while ($row = mysqli_fetch_assoc($result)) {
    $date_name = $row['date_time'];
    $data[$date_name] = array(+$row['ticket_count'], 0);
}

while ($row2 = mysqli_fetch_assoc($result2)) {
    $date_name = $row2['date_time'];
    if (isset($data[$tkc_name])) {
        $data[$date_name][1] = +$row2['ticket_count'];
    }
}
echo json_encode($data);
