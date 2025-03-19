<?php
include('db.php');

// Get default session value from settings table
$settings_query = "SELECT value FROM settings WHERE setting_name = 'default_sessions'";
$settings_result = mysqli_query($con, $settings_query);
$default_sessions = 30; // default fallback value

if($settings_result && mysqli_num_rows($settings_result) > 0) {
    $settings_row = mysqli_fetch_assoc($settings_result);
    $default_sessions = (int)$settings_row['value'];
}

$query = "UPDATE user SET remaining_session = $default_sessions WHERE role = 'user'";

if(mysqli_query($con, $query)) {
    // Log the reset for tracking
    $log_query = "INSERT INTO announce (admin_name, date, message) VALUES ('Admin', NOW(), 'Reset all student sessions to $default_sessions')";
    mysqli_query($con, $log_query);
    echo "success";
} else {
    echo "error";
}

mysqli_close($con);
?>
