<?php
// Start the session
session_start();

// Database connection
include('db.php');
$conn = openConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$userData = null;
$error = null;

// Search logic
if (isset($_GET['search'])) {
    $searchID = trim($_GET['search']); // Remove unnecessary spaces

    if (!empty($searchID)) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, lname, fname, MName, course, level, email, image FROM user WHERE id = ?");
        $stmt->bind_param("s", $searchID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            $error = "No user found with that ID.";
        }
        $stmt->close();
    } else {
        $error = "Please enter a valid ID.";
    }
}

closeConnection($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="admin_Dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="search.php">Search</a></li>
            </ul>
            <a href="../users/index.php" class="btn btn-danger">Log Out</a>
        </div>
    </div>
</nav>

<!-- Search Bar -->
<div class="container mt-4">
    <form method="GET" class="input-group mb-4">
        <input type="text" name="search" class="form-control" placeholder="Enter ID Number..." required>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Display User Data -->
    <?php if ($userData): ?>
    <div class="card p-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-2">
                <?php
                // Check if the image file exists
                $imagePath = !empty($userData['image']) ? 'uploads/' . htmlspecialchars($userData['image']) : '../assets/PERSON.png';
                if (!file_exists($imagePath)) {
                    $imagePath = '../assets/PERSON.png'; // Fallback to placeholder image
                }
                ?>
                <img src="<?php echo $imagePath; ?>" 
                     alt="Profile Image" class="img-fluid rounded-circle" width="100" />
            </div>
            <div class="col-md-10">
                <!-- Data with Padding -->
                <p class="p-2"><strong>Name:</strong> <?php echo htmlspecialchars($userData['fname'] . ' ' . $userData['MName'] . ' ' . $userData['lname']); ?></p>
                <p class="p-2"><strong>ID:</strong> <?php echo htmlspecialchars($userData['id']); ?></p>
                <p class="p-2"><strong>Course:</strong> <?php echo htmlspecialchars($userData['course']); ?></p>
                <p class="p-2"><strong>Year Level:</strong> <?php echo htmlspecialchars($userData['level']); ?></p>
                <p class="p-2"><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
            </div>
        </div>
    </div>
    <?php elseif ($error): ?>
    <div class="alert alert-warning mt-3"> <?php echo $error; ?> </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
