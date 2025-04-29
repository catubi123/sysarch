<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sit_id']) && isset($_POST['user_id'])) {
        $sit_id = intval($_POST['sit_id']);
        $user_id = $_POST['user_id'];
        
        // Start transaction
        $con->begin_transaction();
        
        try {
            // First, check if points column exists
            $check_column = $con->query("SHOW COLUMNS FROM user LIKE 'points'");
            if ($check_column->num_rows === 0) {
                $con->query("ALTER TABLE user ADD points INT DEFAULT 0");
            }

            // Update user points - using COALESCE to handle NULL values
            $update_points = $con->prepare("UPDATE user SET points = COALESCE(points, 0) + 1 WHERE id = ?");
            $update_points->bind_param("s", $user_id);
            $points_result = $update_points->execute();

            if (!$points_result) {
                throw new Exception("Failed to update points: " . $con->error);
            }

            // Time out the sit-in session
            $timeout = $con->prepare("UPDATE student_sit_in SET status = 'Completed', time_out = NOW() WHERE sit_id = ?");
            $timeout->bind_param("i", $sit_id);
            $timeout_result = $timeout->execute();

            if (!$timeout_result) {
                throw new Exception("Failed to update sit-in status: " . $con->error);
            }

            // If both operations successful, commit transaction
            $con->commit();
            $_SESSION['success'] = "Point added and session timed out successfully!";
            
        } catch (Exception $e) {
            // If any operation fails, rollback all changes
            $con->rollback();
            $_SESSION['error'] = "Failed to process request: " . $e->getMessage();
            error_log("Error in add_point.php: " . $e->getMessage());
        }
    } else {
        $_SESSION['error'] = "Missing required parameters";
    }
    
    // Redirect back to sit-in page
    header("Location: sit-in.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: sit-in.php");
    exit();
}
?>
