<?php
include('db.php');

header('Content-Type: application/json');

if (isset($_GET['lab'])) {
    $lab = mysqli_real_escape_string($con, $_GET['lab']);
    
    // Get PC status
    $query = "SELECT * FROM pc_status WHERE lab = '$lab'";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        $pcs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pcs[] = $row;
        }
        
        // Get current reservations
        $today = date('Y-m-d');
        $reservationQuery = "SELECT * FROM reservation 
                           WHERE lab = '$lab' 
                           AND reservation_date = '$today'
                           AND status = 'approved'";
        $reservationResult = mysqli_query($con, $reservationQuery);
        $reservations = [];
        
        while ($row = mysqli_fetch_assoc($reservationResult)) {
            $reservations[] = $row;
        }
        
        echo json_encode([
            'success' => true,
            'pcs' => $pcs,
            'reservations' => $reservations
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => mysqli_error($con)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Lab parameter is required'
    ]);
}
