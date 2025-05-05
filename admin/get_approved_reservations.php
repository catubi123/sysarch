<?php
include('db.php');

$query = "SELECT r.reservation_date, r.lab, r.pc_number, r.id_number, r.purpose, 
          u.fname, u.lname 
          FROM reservation r 
          LEFT JOIN user u ON r.id_number = u.id 
          WHERE r.status = 'approved' 
          ORDER BY r.reservation_date DESC";

$result = $con->query($query);
$data = array();

while ($row = $result->fetch_assoc()) {
    $data['data'][] = array(
        $row['reservation_date'],
        'Lab ' . $row['lab'],
        'PC-' . str_pad($row['pc_number'], 2, '0', STR_PAD_LEFT),
        $row['id_number'] . ' - ' . $row['fname'] . ' ' . $row['lname'], // Added name
        $row['purpose']
    );
}

if (empty($data)) {
    $data['data'] = array();
}

header('Content-Type: application/json');
echo json_encode($data);
?>
