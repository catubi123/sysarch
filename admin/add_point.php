<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sit_id']) && isset($_POST['user_id'])) {
    $sit_id = $_POST['sit_id'];
    $user_id = $_POST['user_id'];
    
    try {
        $con->begin_transaction();
        
        // Get sit-in details
        $stmt = $con->prepare("SELECT sit_lab, pc_number FROM student_sit_in WHERE sit_id = ?");
        $stmt->bind_param("i", $sit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sit_in = $result->fetch_assoc();

        if (!$sit_in) {
            throw new Exception("Sit-in record not found");
        }

        // Add point to user
        $stmt = $con->prepare("UPDATE user SET points = points + 1 WHERE id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // Update sit-in status
        $stmt = $con->prepare("UPDATE student_sit_in SET status = 'Completed', time_out = CURRENT_TIME WHERE sit_id = ?");
        $stmt->bind_param("i", $sit_id);
        $stmt->execute();

        // Update PC status to available
        $stmt = $con->prepare("UPDATE lab_pc SET is_active = 1 WHERE lab = ? AND pc_number = ?");
        $stmt->bind_param("si", $sit_in['sit_lab'], $sit_in['pc_number']);
        $stmt->execute();

        $con->commit();
        $_SESSION['success'] = "Point added successfully and PC marked as available.";
        
    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: sit-in.php");
    exit();
}
?>
