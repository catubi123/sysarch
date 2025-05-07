<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['idNumber', 'lab', 'pc_number', 'purpose', 'date', 'timeIn'];
    $missing_fields = array_filter($required_fields, function($field) {
        return empty($_POST[$field]);
    });

    if (!empty($missing_fields)) {
        $_SESSION['swal_error'] = [
            'title' => 'Missing Fields',
            'text' => 'Please fill in all required fields: ' . implode(', ', $missing_fields),
            'icon' => 'error'
        ];
        header('Location: reservation.php');
        exit;
    }

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
            $_SESSION['swal_success'] = [
                'title' => 'Success!',
                'text' => 'Your reservation has been submitted successfully!',
                'icon' => 'success',
                'details' => [
                    'lab' => $lab,
                    'pc' => $pc_number,
                    'date' => $date,
                    'time' => $time
                ]
            ];
            header('Location: reservation.php');
        } else {
            throw new Exception("Failed to submit reservation");
        }

    } catch (Exception $e) {
        $con->rollback();
        $_SESSION['swal_error'] = [
            'title' => 'Error!',
            'text' => $e->getMessage(),
            'icon' => 'error'
        ];
        header('Location: reservation.php');
    }
} else {
    $_SESSION['swal_error'] = [
        'title' => 'Invalid Request',
        'text' => 'Invalid request method',
        'icon' => 'error'
    ];
    header('Location: reservation.php');
}
exit;
?>
