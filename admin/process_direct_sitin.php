<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $laboratory = $_POST['laboratory'];
    $purpose = $_POST['purpose'];
    $pc_number = $_POST['pc_number'];
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');

    try {
        // Insert into student_sit_in table
        $sql = "INSERT INTO student_sit_in (id_number, sit_lab, sit_purpose, pc_number, sit_date, time_in, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Active')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $student_id, $laboratory, $purpose, $pc_number, $current_date, $current_time);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Direct sit-in successfully recorded!";
        } else {
            throw new Exception("Error recording sit-in");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: sit-in.php");
exit();
?>
