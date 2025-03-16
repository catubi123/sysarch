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
<body class="w3-light-grey">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white active">
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

<div class="container mt-4">
    <div class="row g-4">

        <!-- Profile Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <i class="fas fa-user-circle"></i> Student Information
                </div>
                <div class="card-body text-center">
                    <img src="<?php echo !empty($user_data['image']) ? htmlspecialchars($user_data['image']) : 'PERSON.png'; ?>" 
                         class="rounded-circle border border-3 border-primary mb-3" 
                         style="width: 120px; height: 120px;">
                    <table class="table table-striped">
                        <tr><th>ID:</th><td><?php echo htmlspecialchars($user_data['id']); ?></td></tr>
                        <tr><th>Name:</th><td><?php echo htmlspecialchars($user_data['lname']) . ', ' . htmlspecialchars($user_data['fname']) . ' ' . htmlspecialchars($user_data['MName']); ?></td></tr>
                        <tr><th>Course:</th><td><?php echo htmlspecialchars($user_data['course']); ?></td></tr>
                        <tr><th>Year/Level:</th><td><?php echo htmlspecialchars($user_data['level']); ?></td></tr>
                        <tr><th>Email:</th><td><?php echo htmlspecialchars($user_data['email']); ?></td></tr>
                        <tr><th>Address:</th><td><?php echo htmlspecialchars($user_data['address']); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <i class="fas fa-bullhorn"></i> Announcements
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
    <?php
    $announcement_query = "SELECT admin_name, message, date FROM announce ORDER BY date DESC";
    $announcement_result = $con->query($announcement_query);

    if ($announcement_result->num_rows > 0) {
        $count = 0;
        while ($announcement = $announcement_result->fetch_assoc()) {
            $bgColor = ($count % 2 === 0) ? 'w3-light-grey' : 'w3-white';
            echo "<div class='$bgColor p-3 rounded mb-2'>"; // Adds padding and background
            echo '<p><i class="fas fa-calendar-alt"></i> <b>' . htmlspecialchars($announcement['date']) . '</b></p>';
            echo '<p><i class="fas fa-user"></i> Admin: ' . htmlspecialchars($announcement['admin_name']) . '</p>';
            echo '<p>' . htmlspecialchars($announcement['message']) . '</p>';
            echo '</div>';
            $count++;
        }
    } else {
        echo '<p class="text-center">No announcements available.</p>';
    }
    ?>
</div>

            </div>
        </div>

        <!-- Rules Section -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark text-center">
                    <i class="fas fa-gavel"></i> Rules and Regulations
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <p><i class="fas fa-check-circle text-success"></i> Maintain silence, proper decorum, and discipline inside the laboratory.</p>
                    <p><i class="fas fa-ban text-danger"></i> Games are not allowed inside the lab.</p>
                    <p><i class="fas fa-wifi text-primary"></i> Internet use is only allowed with instructor permission.</p>
                    <p><i class="fas fa-lock text-secondary"></i> Avoid accessing inappropriate websites.</p>
                    <p><i class="fas fa-trash text-danger"></i> Do not delete computer files or change settings.</p>
                    <p><i class="fas fa-clock text-info"></i> Observe computer usage time limits.</p>
                    <p><i class="fas fa-chair text-dark"></i> Return chairs to their proper place after class.</p>
                    <p><i class="fas fa-exclamation-triangle text-warning"></i> For serious offenses, disciplinary action may be taken.</p>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>