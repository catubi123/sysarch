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

// Fetch announcements from the database
$announcements = [];
$conn = openConnection();
$sql = "SELECT * FROM announce ORDER BY announce_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
closeConnection($conn);

$students_registered = 0;
$current_sit_in = 0;
$total_sit_in = 0;
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
        .announcement-item {
            padding: 15px; /* Adds space around each announcement */
            margin-bottom: 10px; /* Separates announcements for better readability */
            border: 1px solid #ddd; /* Adds a light border for definition */
            border-radius: 5px; /* Softens the edges */
            background-color: #f9f9f9; /* Light background for contrast */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for a modern look */
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
            <ul class="list-unstyled mt-3">
                <?php foreach ($announcements as $announcement) { ?>
                    <li class="announcement-item">
                        <strong><?php echo $announcement['date']; ?>:</strong> <?php echo $announcement['message']; ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

<script>
    const ctx2 = document.getElementById('statisticsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['C#', 'C', 'Java', 'ASP.Net', 'Php'],
            datasets: [{
                data: [0.3, 0.4, 0.2, 0.1, 0.5],
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
