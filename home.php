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
        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

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

// Fetch announcements from the database
$announcement_query = "SELECT admin_name, message, date FROM announce ORDER BY date DESC";
$announcement_result = $con->query($announcement_query);
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
            <a href="reservation.php" class="nav-link text-white">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
        </div>
    </div>
</nav>

<div class="w3-third w3-animate-top" style="animation-duration: 0.5s;">
    <div class="w3-card w3-white w3-padding">
        <h3 class="w3-blue w3-padding">
            <i class="fas fa-user-circle"></i> Student Information
        </h3>
        <div class="w3-center">
            <?php 
            $image_path = !empty($user_data['image']) ? htmlspecialchars($user_data['image']) : 'PERSON.png';
            ?>
            <img src="<?php echo $image_path; ?>" class="w3-circle" style="width:100px;height:100px;">
        </div>
        <p><i class="fas fa-id-card"></i> <b>ID:</b> <?php echo htmlspecialchars($user_data['id']); ?></p>
        <p><i class="fas fa-user"></i> <b>Name:</b> <?php echo htmlspecialchars($user_data['lname']) . ', ' . htmlspecialchars($user_data['fname']) . ' ' . htmlspecialchars($user_data['MName']); ?></p>
        <p><i class="fas fa-graduation-cap"></i> <b>Course:</b> <?php echo htmlspecialchars($user_data['course']); ?></p>
        <p><i class="fas fa-layer-group"></i> <b>Year/Level:</b> <?php echo htmlspecialchars($user_data['level']); ?></p>
        <p><i class="fas fa-envelope"></i> <b>Email:</b> <?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><i class="fas fa-map-marker-alt"></i> <b>Address:</b> <?php echo htmlspecialchars($user_data['address']); ?></p>
    </div>
</div>

<!-- Announcements Section -->
<div class="w3-third w3-animate-top" style="animation-duration: 0.5s;">
    <div class="w3-card w3-white w3-padding">
        <h3 class="w3-blue w3-padding w3-card-header">
            <i class="fas fa-bullhorn"></i> Announcement
        </h3>
        <div class="w3-container w3-light-grey w3-padding">
            <?php
            if ($announcement_result->num_rows > 0) {
                while ($announcement = $announcement_result->fetch_assoc()) {
                    echo '<p><i class="fas fa-calendar-alt"></i> <b>' . htmlspecialchars($announcement['date']) . '</b></p>';
                    echo '<p><i class="fas fa-user"></i> Admin: ' . htmlspecialchars($announcement['admin_name']) . '</p>';
                    echo '<p>' . htmlspecialchars($announcement['message']) . '</p>';
                    echo '<hr>'; // Visual separator for announcements
                }
            } else {
                echo '<p>No announcements available.</p>';
            }
            ?>
        </div>
    </div>
</div>

<div class="w3-third">
    <div class="w3-card w3-white w3-padding" style="max-height: 400px; overflow-y: auto;">
        <h3 class="w3-blue w3-padding">
            <i class="fas fa-gavel"></i> Rules and Regulations
        </h3>
        <div class="w3-card-header">
            <p><i class="fas fa-university"></i> <b>University of Cebu</b></p>
            <p><i class="fas fa-book"></i> <b>COLLEGE OF INFORMATION & COMPUTER STUDIES</b></p>
            <p><i class="fas fa-chalkboard-teacher"></i> <b>LABORATORY RULES AND REGULATIONS</b></p>
            <p><i class="fas fa-info-circle"></i> To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
            <ol>
                <li><i class="fas fa-check-circle"></i> Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans, and other personal equipment must be switched off.</li>
                <li><i class="fas fa-ban"></i> Games are not allowed inside the lab. This includes computer-related games, card games, and other games that may disturb the operation of the lab.</li>
                <li><i class="fas fa-wifi"></i> Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing software is strictly prohibited.</li>
                <li><i class="fas fa-lock"></i> Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
                <li><i class="fas fa-trash"></i> Deleting computer files and changing the set-up of the computer is a major offense.</li>
                <li><i class="fas fa-clock"></i> Observe computer time usage carefully. A fifteen-minute allowance is given for each use; otherwise, the unit will be given to those who wish to "sit-in".</li>
                <li><i class="fas fa-users"></i> Observe proper decorum while inside the laboratory.</li>
                <li><i class="fas fa-user-shield"></i> Do not get inside the lab unless the instructor is present.</li>
                <li><i class="fas fa-briefcase"></i> All bags, knapsacks, and the likes must be deposited at the counter.</li>
                <li><i class="fas fa-map-marker-alt"></i> Follow the seating arrangement of your instructor.</li>
                <li><i class="fas fa-times-circle"></i> At the end of class, all software programs must be closed.</li>
                <li><i class="fas fa-chair"></i> Return all chairs to their proper places after using.</li>
                <li><i class="fas fa-ban-smoking"></i> Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
                <li><i class="fas fa-exclamation-circle"></i> Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
                <li><i class="fas fa-shield-alt"></i> Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
                <li><i class="fas fa-phone-alt"></i> For serious offenses, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
                <li><i class="fas fa-tools"></i> Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant, or instructor immediately.</li>
            </ol>
        </div>
        <div class="w3-card w3-padding">
            <h3><i class="fas fa-exclamation-triangle"></i> Disciplinary Action</h3>
            <p><i class="fas fa-flag"></i> <b>First Offense</b> - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
            <p><i class="fas fa-flag-checkered"></i> <b>Second and Subsequent Offenses</b> - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
        </div>
    </div>
</div>

</body>
</html>