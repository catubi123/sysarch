<?php
session_start();
include('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $reservation_id = $_POST['reservation_id'];
    $points = 1; // Default point value
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Add points to user
        $sql = "UPDATE user SET points = points + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $points, $user_id);
        $stmt->execute();
        
        // Update reservation to mark points as given
        $sql = "UPDATE reservation SET points_awarded = 1 WHERE reservation_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        
        $conn->commit();
        $_SESSION['success'] = "Point awarded successfully";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error awarding point: " . $e->getMessage();
    }
}

header("Location: sit-in.php");
exit();
?>
