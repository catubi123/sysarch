<?php
session_start();
include('db.php');
require_once '../check_active_sitin.php';

$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_number = $_POST['id_number'];
    
    // Check for active sit-in
    if (hasActiveSitIn($id_number)) {
        $_SESSION['error'] = "You already have an active sit-in. Please end your current sit-in before starting a new one.";
        header("Location: search.php?search=" . $id_number);
        exit();
    }
    
    // Check remaining sessions
    $check_sessions = "SELECT remaining_session FROM user WHERE id = ?";
    $stmt = $conn->prepare($check_sessions);
    $stmt->bind_param("i", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user['remaining_session'] <= 0) {
        $_SESSION['error'] = "No remaining sessions available";
        header("Location: search.php");
        exit();
    }
    
    // Continue with sit-in processing
    $sit_purpose = $_POST['sit_purpose'];
    $sit_lab = $_POST['sit_lab'];
    $time_in = date('H:i:s');
    $sit_date = date('Y-m-d');
    $status = 'Active';

    $sql = "INSERT INTO student_sit_in (id_number, sit_purpose, sit_lab, time_in, sit_date, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $id_number, $sit_purpose, $sit_lab, $time_in, $sit_date, $status);
    
    if ($stmt->execute()) {
        // Update remaining sessions
        $update_sessions = "UPDATE user SET remaining_session = remaining_session - 1 WHERE id = ?";
        $stmt = $conn->prepare($update_sessions);
        $stmt->bind_param("i", $id_number);
        $stmt->execute();
        
        $_SESSION['success'] = "Sit-in recorded successfully";
    } else {
        $_SESSION['error'] = "Error recording sit-in";
    }
    
    closeConnection($conn);
    header("Location: sit-in.php");
    exit();
}
