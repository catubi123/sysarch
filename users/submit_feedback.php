<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $sit_id = (int)$_POST['sit_in_id'];
    $rating = (int)$_POST['rating'];
    $feedback = $_POST['feedback'];
    $username = $_SESSION['username'];

    // Insert new feedback
    $query = "INSERT INTO feedback (sit_id, user_id, rating, message) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isis", $sit_id, $username, $rating, $feedback);
    
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }
} else {
    echo 'invalid_request';
}
?>