<?php
// Include database connection file
include('db.php');

// Establish connection
$conn = openConnection(); // Make sure this function is correctly defined in db.php

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>