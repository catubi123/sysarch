<?php
include('db.php');

$query = "SELECT r.reservation_date, r.lab, r.pc_number, r.id_number, r.reason 
          FROM reservation r 
          WHERE r.status = 'rejected' 
          ORDER BY r.reservation_date DESC";

$result = $con->query($query);
$data = array();

while ($row = $result->fetch_assoc()) {
    $data['data'][] = array(
        $row['reservation_date'],
        'Lab ' . $row['lab'],
        'PC-' . str_pad($row['pc_number'], 2, '0', STR_PAD_LEFT),
        $row['id_number'],
        $row['reason'] ?? 'No reason provided'
    );
}

if (empty($data)) {
    $data['data'] = array();
}

header('Content-Type: application/json');
echo json_encode($data);
?>
