<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sit_id'])) {
    $sit_id = $_POST['sit_id'];
    $time_out = date('H:i:s');
    
    $sql = "UPDATE student_sit_in SET time_out = ?, status = 'Completed' WHERE sit_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $time_out, $sit_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Student timed out successfully";
    } else {
        $_SESSION['error'] = "Error processing timeout";
    }
    
    $stmt->close();
    closeConnection($conn);
    
    header("Location: sit-in.php");
    exit();
}
