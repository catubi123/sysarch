<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = openConnection();
    
    if (!isset($_POST['action']) || !isset($_POST['id'])) {
        echo "Missing required parameters";
        exit;
    }
    
    $action = $_POST['action'];
    $id = intval($_POST['id']);

    if ($action === 'update') {
        if (!isset($_POST['message']) || empty(trim($_POST['message']))) {
            echo "Message cannot be empty";
            exit;
        }
        
        $message = trim($_POST['message']);
        $stmt = $conn->prepare("UPDATE announce SET message = ? WHERE announce_id = ?");
        $stmt->bind_param("si", $message, $id);
        
        try {
            $result = $stmt->execute();
            echo ($result && $stmt->affected_rows > 0) ? "success" : "no_changes";
        } catch (Exception $e) {
            echo "error";
        }
        
        $stmt->close();
    }
    elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM announce WHERE announce_id = ?");
        $stmt->bind_param("i", $id);
        
        try {
            $result = $stmt->execute();
            echo ($result && $stmt->affected_rows > 0) ? "success" : "not_found";
        } catch (Exception $e) {
            echo "error";
        }
        
        $stmt->close();
    }

    closeConnection($conn);
}
?>
