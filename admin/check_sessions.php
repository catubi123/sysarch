<?php
include('db.php');

$query = "SELECT id, fname, lname, remaining_session FROM user WHERE role = 'user'";
$result = mysqli_query($con, $query);

echo "<h2>Current Session Values:</h2>";
while($row = mysqli_fetch_assoc($result)) {
    echo "ID: {$row['id']} - {$row['fname']} {$row['lname']} - Sessions: {$row['remaining_session']}<br>";
}

mysqli_close($con);
?>
