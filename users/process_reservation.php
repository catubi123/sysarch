<?php
session_start();
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = openConnection();
    
    // Get form data
    $id_number = $_POST['idNumber'];
    $purpose = $_POST['purpose'];
    $lab = $_POST['lab'];
    $date = $_POST['date'];
    $time_in = $_POST['timeIn'];
    $pc_number = $_POST['pc_number'];
    
    // Validate required fields
    if (empty($id_number) || empty($purpose) || empty($lab) || empty($date) || 
        empty($time_in) || empty($pc_number)) {
        $_SESSION['swal_error'] = [
            'title' => 'Error!',
            'text' => 'All fields are required',
            'icon' => 'error'
        ];
        header("Location: reservation.php");
        exit();
    }

    // Check if PC is available
    $check_pc = "SELECT * FROM pc_status WHERE lab_number = ? AND pc_number = ? AND is_active = 0";
    $stmt = $conn->prepare($check_pc);
    $stmt->bind_param("si", $lab, $pc_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['swal_error'] = [
            'title' => 'Error!',
            'text' => 'Selected PC is not available',
            'icon' => 'error'
        ];
        header("Location: reservation.php");
        exit();
    }

    // Modified INSERT query to match the actual table structure
    $query = "INSERT INTO reservation (id_number, lab, pc_number, purpose, reservation_date, reservation_time, status, type) 
              VALUES (?, ?, ?, ?, ?, ?, 'pending', 'reservation')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isisss", $id_number, $lab, $pc_number, $purpose, $date, $time_in);
    
    if ($stmt->execute()) {
        // Update PC status
        $update_pc = "INSERT INTO pc_status (lab_number, pc_number, is_active) 
                      VALUES (?, ?, 0) 
                      ON DUPLICATE KEY UPDATE is_active = 0";
        $stmt = $conn->prepare($update_pc);
        $stmt->bind_param("si", $lab, $pc_number);
        $stmt->execute();

        $_SESSION['swal_success'] = [
            'title' => 'Success!',
            'text' => 'Reservation submitted successfully',
            'icon' => 'success'
        ];
    } else {
        $_SESSION['swal_error'] = [
            'title' => 'Error!',
            'text' => 'Failed to submit reservation: ' . $conn->error,
            'icon' => 'error'
        ];
    }
    
    $conn->close();
    header("Location: reservation.php");
    exit();
}
?>
