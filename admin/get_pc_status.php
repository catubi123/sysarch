<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_GET['lab']) && isset($_GET['pc'])) {
    $stmt = $con->prepare("SELECT is_active FROM pc_status WHERE lab_number = ? AND pc_number = ?");
    $stmt->bind_param("si", $_GET['lab'], $_GET['pc']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // If no record exists, PC is considered available
    echo json_encode(['active' => ($row === null || $row['is_active'] == 1)]);
} else {
    echo json_encode(['active' => true]);
}
