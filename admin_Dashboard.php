<?php
session_start();

$students_registered = 7;
$current_sit_in = 0;
$total_sit_in = 32;

$announcements = [
    ["date" => "2024-May-08", "content" => "We are thrilled to announce the launch of our new CCS system!"],
    ["date" => "2024-May-08", "content" => "We are excited to announce the launch of our new website!"]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>College of Computer Studies Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .highlight-text {
            background-color: #0D47A1;
            color: #FFFFFF;
            padding: 8px 12px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card {
            height: 100%; /* Ensures both cards are the same height */
        }
    </style>
</head>
<body class="bg-light">
<div class="navbar navbar-expand-lg navbar-dark bg-primary rounded p-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fas fa-bars me-2"></i>
            <h2 class="mb-0 text-white"> Admin</h2>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Students</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Sit-in</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">View Sit-in Records</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="student_information.php">View List of Students</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Generate Reports</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Reservation</a></li>
            </ul>
            <button class="btn btn-danger ms-lg-3">Log Out</button>
        </div>
    </div>
</div>

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
            <textarea class="form-control mb-2" placeholder="Type your new announcement here..." rows="4"></textarea>
            <button class="btn btn-success">Submit</button>
            <ul class="list-unstyled mt-3">
                <?php foreach ($announcements as $announcement) { ?>
                    <li><strong><?php echo $announcement['date']; ?>:</strong> <?php echo $announcement['content']; ?></li>
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
