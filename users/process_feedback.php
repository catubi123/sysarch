<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username']) && isset($_POST['feedback'])) {
        $username = $_SESSION['username'];
        $feedback = $_POST['feedback'];
        
        // Add error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Create feedback table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS feedback (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(50),
            message TEXT,
            status VARCHAR(20) DEFAULT 'pending',
            date_submitted DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES user(username)
        )";
        $con->query($create_table);

        // Insert feedback
        $stmt = $con->prepare("INSERT INTO feedback (user_id, message) VALUES (?, ?)");
        if (!$stmt) {
            echo "Prepare failed: " . $con->error;
            exit;
        }
        
        $stmt->bind_param("ss", $username, $feedback);
        
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo "Execute failed: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo 'invalid_input';
    }
} else {
    echo 'invalid_request';
}
?>
