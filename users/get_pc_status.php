<?php
require_once('db.php');
header('Content-Type: application/json');

if (isset($_GET['lab'])) {
    $lab = $_GET['lab'];
    
    $query = "SELECT pc_number as number, is_active 
              FROM lab_pc 
              WHERE lab = ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pcs = [];
    while ($row = $result->fetch_assoc()) {
        $pcs[] = $row;
    }
    
    echo json_encode(['success' => true, 'pcs' => $pcs]);
} else {
    echo json_encode(['success' => false, 'error' => 'Lab not specified']);
}
?>
