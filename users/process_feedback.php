<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    $user_id = $_SESSION['username'];
    $message = mysqli_real_escape_string($con, $_POST['feedback']);
    date_default_timezone_set('Asia/Manila'); // Set timezone
    
    $query = "INSERT INTO feedback (user_id, message, date_submitted) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $user_id, $message);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}
