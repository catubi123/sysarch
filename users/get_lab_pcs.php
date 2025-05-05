<?php
require_once('../admin/db.php');
header('Content-Type: application/json');

if(isset($_GET['lab'])) {
    $lab = $_GET['lab'];
    
    // Get all PCs for the lab, including their status
    $query = "SELECT pn.*, COALESCE(ps.is_active, 1) as is_active 
              FROM pc_numbers pn 
              LEFT JOIN pc_status ps 
              ON pn.lab_number = ps.lab_number 
              AND pn.pc_number = ps.pc_number 
              WHERE pn.lab_number = ? 
              ORDER BY pn.pc_number";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $lab);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pcs = array();
    while($row = $result->fetch_assoc()) {
        $pcs[] = array(
            'pc_number' => $row['pc_number'],
            'is_active' => (bool)$row['is_active']
        );
    }
    
    echo json_encode(['success' => true, 'pcs' => $pcs]);
} else {
    echo json_encode(['success' => false, 'error' => 'Lab not specified']);
}
