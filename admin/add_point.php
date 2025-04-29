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
            // Get current user points and sessions
            $check_user = $con->prepare("SELECT points, remaining_session FROM user WHERE id = ?");
            $check_user->bind_param("s", $user_id);
            $check_user->execute();
            $result = $check_user->get_result();
            $user_data = $result->fetch_assoc();
            
            // Initialize variables with current values or defaults
            $current_points = isset($user_data['points']) ? intval($user_data['points']) : 0;
            $current_sessions = isset($user_data['remaining_session']) ? intval($user_data['remaining_session']) : 0;
            
            // Add new point
            $new_points = $current_points + 1;
            
            // Check if points reached multiple of 3
            if ($new_points % 3 === 0) {
                $current_sessions += 1;
                $_SESSION['success'] = "Point added and earned new session! Total points: $new_points, Sessions: $current_sessions";
            } else {
                $_SESSION['success'] = "Point added successfully! Total points: $new_points";
            }

            // Update user record
            $update_user = $con->prepare("UPDATE user SET points = ?, remaining_session = ? WHERE id = ?");
            $update_user->bind_param("iis", $new_points, $current_sessions, $user_id);
            $update_user->execute();

            // Time out the sit-in session
            $timeout = $con->prepare("UPDATE student_sit_in SET status = 'Completed', time_out = NOW() WHERE sit_id = ?");
            $timeout->bind_param("i", $sit_id);
            $timeout->execute();

            $con->commit();

        } catch (Exception $e) {
            $con->rollback();
            $_SESSION['error'] = "Error processing request: " . $e->getMessage();
            error_log("Error in add_point.php: " . $e->getMessage());
        }
    } else {
        $_SESSION['error'] = "Missing required parameters";
    }
    
    header("Location: sit-in.php");
    exit();
}
?>
