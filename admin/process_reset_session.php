<?php
include('db.php');

// Get default session value from settings or use 30 as fallback
$query = "SELECT value FROM settings WHERE setting_name = 'default_sessions'";
$result = mysqli_query($con, $query);
$default_sessions = ($result && mysqli_num_rows($result) > 0) ? 
                    mysqli_fetch_assoc($result)['value'] : 30;

// Update all users' remaining sessions
$query = "UPDATE user SET remaining_session = ? WHERE role = 'user'";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $default_sessions);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
