<?php
include('db.php');

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }
} else {
    echo "error: No ID provided";
}
?>

