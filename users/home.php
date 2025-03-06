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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body class="w3-light-grey w3-animate-top">

<!-- Navbar -->
<div class="w3-bar w3-blue w3-animate-top" style="animation-duration: 0.5 s ; max-height: 400px; overflow-y: auto;">
    <span class="w3-bar-item w3-large">Dashboard</span>
    <a href="index.php" class="w3-bar-item w3-button w3-right w3-red w3-round-xlarge">Log out</a>
    <a href="#" class="w3-bar-item w3-button w3-right">Reservation</a>
    <a href="edit.php" class="w3-bar-item w3-button w3-right">Edit Profile</a>
    <a href="#" class="w3-bar-item w3-button w3-right">History</a>
    <a href="home.php" class="w3-bar-item w3-button w3-right"><i class="fa-regular fa-circle-user fa-lg text-primary"></i> Home</a>

</div>

<!-- Main Content -->
<div class="w3-row-padding w3-margin-top">

    <!-- Student Information -->
    <div class="w3-third w3-animate-top" style="animation-duration: 0.5s;">
        <div class="w3-card w3-white w3-padding">
            <h3 class="w3-blue w3-padding">Student Information</h3>
            <div class="w3-center">
                <!-- Display Profile Image -->
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
    <div class="w3-third w3-animate-top" style="animation-duration: 0.5 s;">
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
            <h3 class="w3-blue w3-padding ">Rules and Regulation</h3>
            <div class="w3-card-header">
            <p><b     >University of Cebu</b></p>
            <p><b   >COLLEGE OF INFORMATION & COMPUTER STUDIES</b></p>
            <p><b>LABORATORY RULES AND REGULATIONS</b></p>
            <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
            <ol>
                <li>Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</li>
                <li>Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</li>
                <li>Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</li>
                <li>Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
                <li>Deleting computer files and changing the set-up of the computer is a major offense.</li>
                <li>Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</li>
                <li>Observe proper decorum while inside the laboratory.</li>
                <li>Do not get inside the lab unless the instructor is present.</li>
                <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
                <li>Follow the seating arrangement of your instructor.</li>
                <li>At the end of class, all software programs must be closed.</li>
                <li>Return all chairs to their proper places after using.</li>
                <li>Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
                <li>Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
                <li>Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
                <li>For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
                <li>Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</li>
            </ol>
            </div>
            <div  class="w3-card w3-padding> 
                  <h3">Disciplinary Action</h3>
                   <p><b>First Offense</b> - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</p>
                   <p><b>Second and Subsequent Offenses</b> - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</p>
            </div>
        </div>
    </div>


</div>

</body>
</html>
