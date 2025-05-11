<?php
session_start();
include('db.php');

header('Content-Type: application/json');
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    try {
        $sit_id = (int)$_POST['sit_in_id'];
        $rating = (int)$_POST['rating'];
        $feedback = $_POST['feedback'];
        $user_id = $_SESSION['user_id'];

        // Check if feedback already exists
        $check_sql = "SELECT id FROM feedback WHERE sit_id = ? AND user_id = ?";
        $check_stmt = $con->prepare($check_sql);
        $check_stmt->bind_param("ii", $sit_id, $user_id);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows > 0) {
            $response['error'] = 'Feedback already submitted';
            echo json_encode($response);
            exit;
        }

        // Insert new feedback
        $query = "INSERT INTO feedback (sit_id, user_id, rating, message) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("iiis", $sit_id, $user_id, $rating, $feedback);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Feedback submitted successfully';
        } else {
            $response['error'] = $stmt->error;
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }
} else {
    $response['error'] = 'Invalid request';
}

echo json_encode($response);
?>