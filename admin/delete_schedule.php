<?php
session_start();
require_once('db.php');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No schedule specified for deletion";
    header("Location: lab_schedules.php");
    exit();
}

$schedule_id = intval($_GET['id']);
$query = "DELETE FROM lab_schedules WHERE schedule_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $schedule_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Schedule deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting schedule";
}

header("Location: lab_schedules.php");
exit();
