<?php
session_start();
include('db.php');

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You need to log in first!'); window.location.href = 'index.php';</script>";
    exit();
}

// Fetch user data using username
$username = $_SESSION['username'];
$query = "SELECT id, lname, fname, course, level FROM user WHERE username = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found!'); window.location.href = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="w3.css">
</head>
<body class="w3-light-grey w3-animate-top">

<!-- Navbar -->
<div class="w3-bar w3-cyan w3-animate-top" style="animation-duration: 0.5s;">
    <span class="w3-bar-item w3-large">Dashboard</span>
    <a href="index.php" class="w3-bar-item w3-button w3-right w3-red w3-round-xlarge ">Log out</a>
    <a href="#" class="w3-bar-item w3-button w3-right">Reservation</a>
    <a href="#" class="w3-bar-item w3-button w3-right">Edit Profile</a>
    <a href="#" class="w3-bar-item w3-button w3-right">History</a>
    <a href="#" class="w3-bar-item w3-button w3-right">Home</a>
</div>

<!-- Main Content -->
<div class="w3-row-padding w3-margin-top">

    <!-- Student Information -->
    <div class="w3-third w3-animate-top" style="animation-duration: 2s;">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding">Student Information</h3>
            <div class="w3-center">
                <img src="PERSON.png" class="w3-circle" style="width:100px;height:100px;">
            </div>
            <p><b>ID:</b> <?php echo htmlspecialchars($user_data['id']); ?></p>
            <p><b>Name:</b> <?php echo htmlspecialchars($user_data['lname'] . ', ' . $user_data['fname']); ?></p>
            <p><b>Course:</b> <?php echo htmlspecialchars($user_data['course']); ?></p>
            <p><b>Year:</b> <?php echo htmlspecialchars($user_data['level']); ?></p>
        </div>
    </div>

    <!-- Announcements -->
    <div class="w3-third w3-animate-top" style="animation-duration: 0.8s;">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding">Announcement</h3>
            <div class="w3-container w3-light-grey w3-padding">
                <p><b>CCS Feb 09 2025</b></p>
                <p> Soon To be Announced 🚀</p>
            </div>
            
            <!-- Feedback Form -->
            <div class="w3-card w3-margin-top w3-animate-top" style="animation-duration: 0.8s;">
                <h4 class="w3-blue w3-padding">Report</h4>
                <form action="feedback.php" method="POST">
                    <input class="w3-input w3-border" type="text" name="feedback" placeholder="Enter Feedback">
                    <button type="submit" class="w3-button w3-blue w3-margin-top">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rules and Regulations -->
    <div class="w3-third">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding">UC Main Campus</h3>
            <img src="uc.jpg" class="w3-image" style="width:100%;">
        </div>
    </div>

</div>

</body>
</html>
