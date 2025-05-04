<?php
include('../users/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lab = $_POST['lab'];
    $pc = $_POST['pc'];
    
    // Check if PC exists in status table
    $check_query = "SELECT id FROM pc_status WHERE lab_number = ? AND pc_number = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("si", $lab, $pc);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Toggle availability
        $toggle_query = "UPDATE pc_status SET is_available = NOT is_available WHERE lab_number = ? AND pc_number = ?";
        $toggle_stmt = $con->prepare($toggle_query);
        $toggle_stmt->bind_param("si", $lab, $pc);
        $success = $toggle_stmt->execute();
    } else {
        // Insert new PC with available status
        $insert_query = "INSERT INTO pc_status (lab_number, pc_number, is_available) VALUES (?, ?, 1)";
        $insert_stmt = $con->prepare($insert_query);
        $insert_stmt->bind_param("si", $lab, $pc);
        $success = $insert_stmt->execute();
    }
    
    echo $success ? "success" : "error";
}
?>
