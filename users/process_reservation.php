<?php
session_start();
require_once('db.php');

header('Content-Type: application/json'); // Change to JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['idNumber'];
    $lab = $_POST['lab'];
    $pc_number = $_POST['pc_number'];
    $purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $time = $_POST['timeIn'];
    $status = 'pending';
    $created_at = date('Y-m-d H:i:s');

    try {
        // Start transaction
        $con->begin_transaction();

        // Insert reservation
        $stmt = $con->prepare("INSERT INTO reservation 
            (id_number, lab, pc_number, purpose, reservation_date, 
             reservation_time, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sissssss", 
            $id_number, $lab, $pc_number, $purpose, 
            $date, $time, $status, $created_at
        );

        if ($stmt->execute()) {
            $con->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Your reservation has been submitted successfully!'
            ]);
        } else {
            throw new Exception("Failed to submit reservation");
        }

    } catch (Exception $e) {
        $con->rollback();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
