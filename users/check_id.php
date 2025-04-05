<?php
include('db.php');

if(isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    
    $query = "SELECT id FROM user WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    echo mysqli_num_rows($result) > 0 ? 'exists' : 'available';
} else {
    echo 'error';
}
?>
