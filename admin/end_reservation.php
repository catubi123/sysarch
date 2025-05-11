<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    
    // Update reservation status and set actual time out
    $sql = "UPDATE reservation SET 
            status = 'completed',
            actual_time_out = CURRENT_TIMESTAMP
            WHERE reservation_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Reservation completed successfully";
    } else {
        $_SESSION['error'] = "Error completing reservation";
    }
}

header("Location: sit-in.php");
exit();
?>
