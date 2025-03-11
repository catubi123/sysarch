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
$query = "SELECT id, lname, fname, MName, email, course, level, image, address FROM user WHERE username = ?";
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

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $MName = $_POST['MName'];
    $course = $_POST['course'];
    $level = $_POST['level'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Handle image upload
    $image_path = $user_data['image']; 
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ["jpg", "jpeg", "png", "gif,"];

        if (in_array($imageFileType, $allowed_extensions)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.');</script>";
        }
    }

    // Update user data
    $update_query = "UPDATE user SET lname = ?, fname = ?, MName = ?, email = ?, course = ?, level = ?, image = ?, address = ? WHERE username = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("sssssssss", $lname, $fname, $MName, $email, $course, $level, $image_path, $address, $username);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'home.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="w3-light-grey w3-animate-top">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-history"></i> History
            </a>
            <a href="edit.php" class="nav-link text-white">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="w3-row-padding w3-margin-top">

    <!-- Student Information -->
    <div class="w3-third w3-animate-top" style="animation-duration: 0.5s;">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding">Student Information</h3>
            <div class="w3-center">
                <?php 
                $image_path = !empty($user_data['image']) ? htmlspecialchars($user_data['image']) : 'PERSON.png';
                ?>
                <img src="<?php echo $image_path; ?>" class="w3-circle" style="width:100px;height:100px;">
            </div>
            <p><b>ID:</b> <?php echo htmlspecialchars($user_data['id']); ?></p>
            <p><b>Name:</b> <?php echo htmlspecialchars($user_data['lname']) . ', ' . htmlspecialchars($user_data['fname']) . ' ' . htmlspecialchars($user_data['MName']); ?></p>
            <p><b>Course:</b> <?php echo htmlspecialchars($user_data['course']); ?></p>
            <p><b>Year/Level:</b> <?php echo htmlspecialchars($user_data['level']); ?></p>
            <p><b>Email:</b> <?php echo htmlspecialchars($user_data['email']); ?></p>
            <p><b>Address:</b> <?php echo htmlspecialchars($user_data['address']); ?></p>
        </div>
    </div>

    <!-- Announcements -->
    <div class="w3-third w3-animate-top" style="animation-duration: 0.5s;">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding w3-card-header">Announcement</h3>
            <div class="w3-container w3-light-grey w3-padding">
                <p><b>CCS Feb 09 2025</b></p>
                <p> Soon To be Announced 🚀</p>
            </div>
        </div>
    </div>

    <!-- Rules and Regulations -->
    <div class="w3-third">
        <div class="w3-card w3-white w3-padding" style="max-height: 400px; overflow-y: auto;">
            <h3 class="w3-blue w3-padding">Rules and Regulation</h3>
            <div class="w3-card-header">
                <p><b>University of Cebu</b></p>
                <p><b>COLLEGE OF INFORMATION & COMPUTER STUDIES</b></p>
                <p><b>LABORATORY RULES AND REGULATIONS</b></p>
                <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                <ol>
                    <li>Maintain silence, proper decorum, and discipline inside the laboratory...</li>
                    <li>Games are not allowed inside the lab...</li>
                    <li>Surfing the Internet is allowed only with permission...</li>
                </ol>
            </div>
            <div class="w3-card w3-padding"> 
                <h3>Disciplinary Action</h3>
                <p><b>First Offense</b> - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                <p><b>Second and Subsequent Offenses</b> - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>