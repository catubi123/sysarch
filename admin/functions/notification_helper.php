<?php
function insertNotification($con, $id_number, $message) {
    $query = "INSERT INTO notification (id_number, message) VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $id_number, $message);
    return $stmt->execute();
}
