<?php
session_start();
require_once('db.php');
$conn = openConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sit_id'])) {
    $sit_id = $_POST['sit_id'];
    
    try {
        $conn->begin_transaction();
        
        // First get the sit-in details to know which PC to update
        $stmt = $conn->prepare("SELECT sit_lab, pc_number FROM student_sit_in WHERE sit_id = ?");
        $stmt->bind_param("i", $sit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sit_in = $result->fetch_assoc();

        if (!$sit_in) {
            throw new Exception("Sit-in record not found");
        }

        // Update sit-in status to 'Completed'
        $stmt = $conn->prepare("UPDATE student_sit_in SET status = 'Completed', time_out = CURRENT_TIME WHERE sit_id = ?");
        $stmt->bind_param("i", $sit_id);
        $stmt->execute();

        // Update PC status to available (is_active = 1)
        $stmt = $conn->prepare("UPDATE lab_pc SET is_active = 1 WHERE lab = ? AND pc_number = ?");
        $stmt->bind_param("si", $sit_in['sit_lab'], $sit_in['pc_number']);
        $stmt->execute();

        $conn->commit();
        $_SESSION['success'] = "Student timed out successfully and PC marked as available.";
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header("Location: sit-in.php");
    exit();
}
