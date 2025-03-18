<?php
session_start();
include 'db.php'; // Include your database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_name = 'Mark'; // Replace with actual admin name if available
    $date = date('Y-m-d');
    $message = $_POST['message'];

    $conn = openConnection();
    $stmt = $conn->prepare("INSERT INTO announce (admin_name, date, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_name, $date, $message);
    $stmt->execute();
    $stmt->close();
    closeConnection($conn);

    // Redirect to avoid form resubmission
    header("Location: admin_Dashboard.php");
    exit();
}

// Fetch statistics from database
$conn = openConnection();

// Get total registered students from user table
$student_query = "SELECT COUNT(*) as count FROM user WHERE role = 'student'";
$student_result = $conn->query($student_query);
$students_registered = $student_result->fetch_assoc()['count'];

// Get current active sit-ins
$active_query = "SELECT COUNT(*) as count FROM student_sit_in WHERE status = 'Active'";
$active_result = $conn->query($active_query);
$current_sit_in = $active_result->fetch_assoc()['count'];

// Get total sit-ins from student_sit_in table
$total_query = "SELECT COUNT(*) as count FROM student_sit_in";
$total_result = $conn->query($total_query);
$total_sit_in = $total_result->fetch_assoc()['count'];

// Update pie chart data based on sit-in purposes
$purpose_query = "SELECT sit_purpose, COUNT(*) as count FROM student_sit_in GROUP BY sit_purpose";
$purpose_result = $conn->query($purpose_query);
$purposes = [];
$purpose_counts = [];
while($row = $purpose_result->fetch_assoc()) {
    $purposes[] = $row['sit_purpose'];
    $purpose_counts[] = $row['count'];
}

// Fetch announcements from the database
$announcements = [];
$sql = "SELECT * FROM announce ORDER BY announce_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
closeConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>College of Computer Studies Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .highlight-text {
            background-color: #0d6efd;
            color: #FFFFFF;
            padding: 8px 12px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card {
            height: 100%; /* Ensures both cards are the same height */
        }
        .announcement-container {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
            padding-right: 5px;
        }
        .announcement-item {
            padding: 15px; /* Adds space around each announcement */
            margin-bottom: 10px; /* Separates announcements for better readability */
            border: 1px solid #ddd; /* Adds a light border for definition */
            border-radius: 5px; /* Softens the edges */
            background-color: #f9f9f9; /* Light background for contrast */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for a modern look */
        }
        /* Custom scrollbar styling */
        .announcement-container::-webkit-scrollbar {
            width: 8px;
        }
        .announcement-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .announcement-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .announcement-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-light">
<?php include 'admin_navbar.php' ?>
<div class="row">
    <div class="col-md-6">
        <div class="card p-3 mb-3">
            <h4 class="highlight-text">Statistics</h4>
            <p>Students Registered: <?php echo $students_registered; ?></p>
            <p>Currently Sit-in: <?php echo $current_sit_in; ?></p>
            <p>Total Sit-in: <?php echo $total_sit_in; ?></p>
            <div class="d-flex justify-content-center">
                <canvas id="statisticsChart" style="max-width: 400px; max-height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3 mb-3">
            <h4 class="highlight-text">Announcement</h4>
            <form method="POST" action="">
                <textarea class="form-control mb-2" name="message" placeholder="Type your new announcement here..." rows="4" required></textarea>
                <button type="submit" class="btn btn-success">Create</button>
            </form>
            <div class="announcement-container">
                <ul class="list-unstyled">
                    <?php foreach ($announcements as $announcement) { ?>
                        <li class="announcement-item">
                            <strong><?php echo $announcement['date']; ?>:</strong> <?php echo $announcement['message']; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx2 = document.getElementById('statisticsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($purposes); ?>,
            datasets: [{
                data: <?php echo json_encode($purpose_counts); ?>,
                backgroundColor: ['#36A2EB', '#FF6384', '#FF9F40', '#FFCE56', '#4BC0C0']
            }]
        },
        options: {
            maintainAspectRatio: false, // Ensures size flexibility
            responsive: true
        }
    });
</script>
</body>
</html>
