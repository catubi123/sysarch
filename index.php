<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password']; // Do NOT hash the input password here

    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            
            // Verify the hashed password
            if (password_verify($password, $user_data["password"])) {
                $_SESSION['username'] = $username;
                echo "<script>alert('Log in Successfully!!');</script>";

                header("Location: home.php");
                exit();
            } else {
                echo "<script>alert('❌ Invalid username or password!');</script>";
            }
        } else {
            echo "<script>alert('❌ Invalid username or password!');</script>";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database error!');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <title>CCS Sit In Monitoring System</title>
    <link rel="stylesheet" href="w3.css">
</head>
<body class="w3-blue">
    <div class="w3-card-4 w3-white w3-padding w3-round-xxlarge w3-animate-top" style="max-width:400px;margin:auto;margin-top:50px;">
    <div class="w3-center">
            <img src="ccs.png" alt="Image 1" class="w3-circle" style="width:80px;height:80px;margin:10px;">
            <img src="ucmain.jpg" alt="Image 2" class="w3-circle" style="width:80px;height:80px;margin:10px;">
        </div>
        <h2 class="w3-center">CCS Sit Monitoring System</h2>
        <form method="POST">
            <label>Username</label>
            <input class="w3-input w3-border" type="text" name="username"  placeholder="Enter username" required>
            <label>Password</label>
            <input class="w3-input w3-border" type="password" name="password"  placeholder="Enter password" required>
            <p><button class="w3-button w3-cyan w3-round-xlarge">Login</button></p>

            <div class="container signin">
                <p>Dont't have an account? <a href="signup.php">Register</a>.</p>
              </div>
        </form>
        
    </div>
</body>
</html>

