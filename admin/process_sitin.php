<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_number = $_POST['id_number'];
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
        $_SESSION['success'] = "Sit-in recorded successfully";
    } else {
        $_SESSION['error'] = "Error recording sit-in";
    }
    
    $stmt->close();
    closeConnection($conn);
    
    header("Location: sit-in.php");
    exit();
}
