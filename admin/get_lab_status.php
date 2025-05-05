<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_GET['lab'])) {
    $stmt = $con->prepare("SELECT pc_number, is_active FROM pc_status WHERE lab_number = ?");
    $stmt->bind_param("s", $_GET['lab']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pcs = array();
    while($row = $result->fetch_assoc()) {
        $pcs[] = array(
            'number' => $row['pc_number'],
            'is_active' => (bool)$row['is_active']
        );
    }
    
    echo json_encode(['pcs' => $pcs]);
} else {
    echo json_encode(['error' => 'Lab not specified']);
}
