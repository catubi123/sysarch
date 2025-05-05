<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_GET['lab']) && isset($_GET['pc'])) {
    // First check if PC exists
    $stmt = $con->prepare("SELECT 1 FROM pc_numbers WHERE lab_number = ? AND pc_number = ?");
    $stmt->bind_param("si", $_GET['lab'], $_GET['pc']);
    $stmt->execute();
    
    if($stmt->get_result()->num_rows === 0) {
        // If PC doesn't exist in pc_numbers, add it
        $stmt = $con->prepare("INSERT INTO pc_numbers (lab_number, pc_number) VALUES (?, ?)");
        $stmt->bind_param("si", $_GET['lab'], $_GET['pc']);
        $stmt->execute();
    }

    // Get PC status
    $stmt = $con->prepare("SELECT is_active FROM pc_status WHERE lab_number = ? AND pc_number = ?");
    $stmt->bind_param("si", $_GET['lab'], $_GET['pc']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode([
        'active' => ($row === null || $row['is_active'] == 1),
        'lab' => $_GET['lab'],
        'pc' => $_GET['pc']
    ]);
} else {
    echo json_encode(['active' => true]);
}
