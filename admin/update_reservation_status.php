<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    
    $query = "UPDATE reservations SET status = '$status' WHERE id = '$id'";
    $result = mysqli_query($con, $query);
    
    echo $result ? 'success' : 'error';
}
