<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $MName = mysqli_real_escape_string($con, $_POST['MName']);
    $Course = mysqli_real_escape_string($con, $_POST['Course']);
    $Level = mysqli_real_escape_string($con, $_POST['Level']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    $query = "INSERT INTO user (id, lname, fname, MName, Course, Level, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $id, $lname, $fname, $MName, $Course, $Level, $username, $password);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('An error occurred!');</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Database error!";
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="w3.css">
</head>
<body class="w3-cyan" >
    <div class="w3-card-4 w3-white w3-padding w3-round-xxlarge w3-animate-top" style="max-width:420px;margin:auto;margin-top:30px;">
        <h2 class="w3-center">Registration</h2>
        <form method="post">
            <label>IDNO</label>
            <input class="w3-input w3-border w3-large " type="number" name="id" required>
            <label>Lastname</label>
            <input class="w3-input w3-border w3-large  " type="text" name="lname" required>
            <label>Firstname</label>
            <input class="w3-input w3-border w3-large " type="text" name="fname" required>
            <label>MiddleName</label>
            <input class="w3-input w3-border w3-large " type="text" name="MName" >
            <label>Course</label>
            <select class="w3-input w3-border  w3-large"  name="Course" required>
            <option value=""></option>
            <option value="BSED">BSED</option>
            <option value="BSIT">BSIT</option>
            <option value="BSCPE">BSCPE</option>
            <option value="BSCRIM">BSCRIM</option>
            <option value="BSCA">BSCA</option>
            <option value="BSCS">BSCS</option>
            <option value="BPED">BPED</option>
            
            </select>
            <label>Yr/Level</label>
            <select class="w3-input w3-border  w3-large " name="Level" required>
            <option value=""></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            </select>
            <label>Username</label>
            <input class="w3-input w3-border w3-large " type="text" name="username" required>
            <label>password</label>
            <input class="w3-input w3-border w3-large " type="password" name="password" required>

            <p><button class="w3-button w3-cyan w3-round-xlarge">Register</button></p>

            
            <div class="container signin">
                <p>Already have an account? <a href="index.php">Log in</a>.</p>
              </div>
        </form> 
    </div>
</body>
</html>
