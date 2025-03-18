<?php
session_start();
include('db.php');
$conn = openConnection();

// Get filter parameters
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build the SQL query with filters
$sql = "SELECT s.*, u.fname, u.lname, u.course, u.level 
        FROM student_sit_in s
        JOIN user u ON s.id_number = u.id
        WHERE 1=1";

if ($date_filter) {
    $sql .= " AND s.sit_date = '$date_filter'";
}
if ($status_filter) {
    $sql .= " AND s.status = '$status_filter'";
}

$sql .= " ORDER BY s.sit_date DESC, s.time_in DESC";
$result = $conn->query($sql);

// Get purpose statistics
$purpose_sql = "SELECT sit_purpose, COUNT(*) as count FROM student_sit_in GROUP BY sit_purpose";
$purpose_result = $conn->query($purpose_sql);
$purposes = array();
$purpose_counts = array();
while($row = $purpose_result->fetch_assoc()) {
    $purposes[] = $row['sit_purpose'];
    $purpose_counts[] = $row['count'];
}

// Get laboratory statistics
$lab_sql = "SELECT sit_lab, COUNT(*) as count FROM student_sit_in GROUP BY sit_lab";
$lab_result = $conn->query($lab_sql);
$labs = array();
$lab_counts = array();
while($row = $lab_result->fetch_assoc()) {
    $labs[] = $row['sit_lab'];
    $lab_counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-white">
    <?php include 'admin_navbar.php' ?>
    
    <div class="container mt-4">
        <h2>Sit-in Records</h2>

        <!-- Add charts container before filters -->
        <div class="row mb-4 justify-content-center">

        <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body" style="height: 300px;">
                        <h5 class="card-title text-center">Purpose Distribution</h5>
                        <canvas id="purposeChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body" style="height: 300px;">
                        <h5 class="card-title text-center">Laboratory Distribution</h5>
                        <canvas id="labChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Centered Filters -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <form method="GET" class="row g-3">
                    <div class="col-md-5">
                        <input type="date" name="date" class="form-control" value="<?php echo $date_filter; ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active" <?php echo $status_filter == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Completed" <?php echo $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-center">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="sit-in-records.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="recordsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Purpose</th>
                        <th>Laboratory</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['level']); ?></td>
                        <td><?php echo htmlspecialchars($row['sit_purpose']); ?></td>
                        <td><?php echo htmlspecialchars($row['sit_lab']); ?></td>
                        <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                        <td><?php echo $row['time_out'] ? htmlspecialchars($row['time_out']) : '-'; ?></td>
                        <td><?php echo htmlspecialchars($row['sit_date']); ?></td>
                        <td>
                            <span class="badge <?php echo $row['status'] == 'Active' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#recordsTable').DataTable({
                "order": [[8, "desc"], [6, "desc"]], // Sort by date and time
                "pageLength": 25
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 0,
                        bottom: 10
                    }
                }
            };

            // Purpose Chart
            const ctxPurpose = document.getElementById('purposeChart').getContext('2d');
            new Chart(ctxPurpose, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($purposes); ?>,
                    datasets: [{
                        data: <?php echo json_encode($purpose_counts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: chartOptions
            });

            // Laboratory Chart
            const ctxLab = document.getElementById('labChart').getContext('2d');
            new Chart(ctxLab, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($labs); ?>,
                    datasets: [{
                        data: <?php echo json_encode($lab_counts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                    }]
                },
                options: chartOptions
            });
        });
    </script>
</body>
</html>
