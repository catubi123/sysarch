<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['idNumber'];
    $date = $_POST['date'];
    $time = $_POST['timeIn'];
    $lab = $_POST['lab'];
    $purpose = $_POST['purpose'];
    $status = 'pending'; // Default status for new reservations

    // Check if the user already has 3 reservations for the selected date
    $check_limit_query = "SELECT COUNT(*) as reservation_count 
                          FROM reservation 
                          WHERE id_number = ? 
                          AND reservation_date = ? 
                          AND reservation_time BETWEEN '08:00:00' AND '17:00:00'";
    
    $stmt = $con->prepare($check_limit_query);
    $stmt->bind_param("ss", $id_number, $date);
    $stmt->execute();
    $limit_result = $stmt->get_result()->fetch_assoc();

    if ($limit_result['reservation_count'] >= 3) {
        $_SESSION['error'] = "You can only make up to 3 reservations between 8:00 AM and 5:00 PM on the same day.";
        header("Location: reservation.php");
        exit();
    }

    // Check for existing reservation at the same time and lab
    $check_query = "SELECT * FROM reservation 
                    WHERE reservation_date = ? 
                    AND lab = ? 
                    AND reservation_time = ?";
    
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("sss", $date, $lab, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "This time slot is already reserved.";
        header("Location: reservation.php");
        exit();
    }

    // Insert new reservation
    $query = "INSERT INTO reservation (reservation_date, reservation_time, lab, purpose, 
              id_number, status) VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssss", $date, $time, $lab, $purpose, $id_number, $status);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Reservation submitted successfully!";
    } else {
        $_SESSION['error'] = "Error submitting reservation.";
    }

    header("Location: reservation.php");
    exit();
}
?>
