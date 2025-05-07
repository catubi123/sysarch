<?php
session_start();
require_once('db.php');

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
        // Check if PC is available
        $check_pc = $con->prepare("SELECT is_active FROM lab_pc WHERE lab = ? AND pc_number = ?");
        $check_pc->bind_param("si", $lab, $pc_number);
        $check_pc->execute();
        $result = $check_pc->get_result();
        $pc_status = $result->fetch_assoc();

        if (!$pc_status || !$pc_status['is_active']) {
            throw new Exception("Selected PC is not available");
        }

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
            $_SESSION['swal_success'] = [
                'title' => 'Success!',
                'text' => 'Your reservation has been submitted and is pending approval.',
                'icon' => 'success'
            ];
        } else {
            throw new Exception("Failed to submit reservation");
        }

        header("Location: home.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['swal_error'] = [
            'title' => 'Error!',
            'text' => $e->getMessage(),
            'icon' => 'error'
        ];
        header("Location: reservation.php");
        exit();
    }
} else {
    header("Location: reservation.php");
    exit();
}
?>
