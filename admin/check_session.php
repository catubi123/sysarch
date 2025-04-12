<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    
    $query = "SELECT remaining_session FROM user WHERE id = '$id'";
    $result = mysqli_query($con, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $response = array(
            'hasMaxSession' => ($row['remaining_session'] >= 30),
            'currentSessions' => $row['remaining_session']
        );
    } else {
        $response = array('error' => 'Student not found');
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
