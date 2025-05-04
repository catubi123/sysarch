<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pc_number = $_POST['pc_number'];
    $lab = $_POST['lab'];
    $action = $_POST['action'];

    $query = "INSERT INTO pc_status (lab, pc_number, status) 
              VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE status = ?";
    
    $status = $action === 'check' ? 1 : 0;
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("siii", $lab, $pc_number, $status, $status);
    
    echo json_encode(['success' => $stmt->execute()]);
}
?>
