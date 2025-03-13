<?php
session_start();

$students_registered = 7;
$current_sit_in = 0;
$total_sit_in = 32;

$year_levels = [
    'Freshmen' => 2,
    'Sophomore' => 1,
    'Junior' => 3,
    'Senior' => 2
];

$announcements = [
    ["date" => "2024-May-08", "content" => "We are thrilled to announce the launch of our new CCS system!"],
    ["date" => "2024-May-08", "content" => "We are excited to announce the launch of our new website!"]
];

$feedback = [
    ["id" => "19835644", "date" => "2024-May-08", "content" => "Ang lab 524 kay bati"]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>College of Computer Studies Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { max-width: 1920px; margin: 0 auto; padding: 0; }
        .navbar-custom { background-color: #0D47A1; }
        .navbar-custom a { color: #FFFFFF; margin-right: 15px; text-decoration: none; transition: color 0.3s; }
        .navbar-custom a:hover { color: #FFC107; }
        .btn-logout { background-color: #DC3545; color: #FFFFFF; border: none; }
        .highlight-text { background-color: #0D47A1; color: #FFFFFF; padding: 8px 12px; border-top-left-radius: 10px; border-top-right-radius: 10px; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="navbar navbar-custom p-3 rounded mb-3 d-flex justify-content-between align-items-center">
            <h2 class="text-white">College of Computer Studies Admin</h2>
            <nav class="d-flex">
                <a href="#">Home</a>
                <a href="search.php">Search</a>
                <a href="#">Students</a>
                <a href="#">Sit-in</a>
                <a href="#">View Sit-in Records</a>
                <a href="#">Generate Reports</a>
                <a href="#">Reservation</a>
            </nav>
            <button class="btn btn-logout">Log Out</button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card p-3 mb-3">
                    <h4 class="highlight-text">Statistics</h4>
                    <p>Students Registered: <?php echo $students_registered; ?></p>
                    <p>Currently Sit-in: <?php echo $current_sit_in; ?></p>
                    <p>Total Sit-in: <?php echo $total_sit_in; ?></p>
                    <canvas id="statisticsChart"></canvas>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-3 mb-3">
                    <h4 class="highlight-text">Announcement</h4>
                    <input type="text" class="form-control mb-2" placeholder="New Announcement">
                    <button class="btn btn-success">Submit</button>
                    <ul class="list-unstyled mt-3">
                        <?php foreach ($announcements as $announcement) { ?>
                            <li><strong><?php echo $announcement['date']; ?>:</strong> <?php echo $announcement['content']; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <h4 class="highlight-text">Feedback and Reports</h4>
            <ul class="list-unstyled">
                <?php foreach ($feedback as $fb) { ?>
                    <li><strong><?php echo $fb['id']; ?> | <?php echo $fb['date']; ?>:</strong> <?php echo $fb['content']; ?></li>
                <?php } ?>
            </ul>
        </div>

        <div class="card p-3">
            <h4 class="highlight-text">Students Year Level</h4>
            <canvas id="yearLevelChart"></canvas>
        </div>
    </div>

    <script>
        const ctx1 = document.getElementById('yearLevelChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Freshmen', 'Sophomore', 'Junior', 'Senior'],
                datasets: [{
                    label: 'Year Levels',
                    data: [2, 1, 3, 2],
                    backgroundColor: ['#FF6384', '#FFCE56', '#36A2EB', '#4BC0C0']
                }]
            }
        });

        const ctx2 = document.getElementById('statisticsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['C#', 'C', 'Java', 'ASP.Net', 'Php'],
                datasets: [{
                    data: [0.3, 0.4, 0.2, 0.1, 0.5],
                    backgroundColor: ['#36A2EB', '#FF6384', '#FF9F40', '#FFCE56', '#4BC0C0']
                }]
            }
        });
    </script>
</body>
</html>
