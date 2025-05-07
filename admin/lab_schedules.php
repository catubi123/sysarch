<?php
session_start();
require_once('db.php');
include('admin_navbar.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lab_number = $_POST['lab_number'];
    $professor = $_POST['professor'];
    $subject = $_POST['subject'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query = "INSERT INTO lab_schedules (lab_number, professor_name, subject, day, start_time, end_time) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssss", $lab_number, $professor, $subject, $day, $start_time, $end_time);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Schedule added successfully!";
    } else {
        $_SESSION['error'] = "Error adding schedule: " . $con->error;
    }
    header("Location: lab_schedules.php");
    exit();
}

// Fetch existing schedules
$schedules = $con->query("SELECT * FROM lab_schedules ORDER BY day, start_time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #dee2e6;
            padding: 10px;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
            border-color: #86b7fe;
        }
        .btn-gradient {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .schedule-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .schedule-table th {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
            padding: 15px;
        }
        .schedule-table td {
            padding: 12px;
            vertical-align: middle;
        }
        .schedule-table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .badge-custom {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
        }
        .day-column {
            min-width: 120px;
        }
        .time-column {
            min-width: 150px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Schedule</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="scheduleForm" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-building me-2"></i>Laboratory
                                </label>
                                <select name="lab_number" class="form-select" required>
                                    <option value="">Select Laboratory</option>
                                    <option value="524">Lab 524</option>
                                    <option value="526">Lab 526</option>
                                    <option value="528">Lab 528</option>
                                    <option value="530">Lab 530</option>
                                    <option value="542">Lab 542</option>
                                    <option value="544">Lab 544</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user-tie me-2"></i>Professor Name
                                </label>
                                <input type="text" name="professor" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-book me-2"></i>Subject Name
                                </label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar me-2"></i>Day
                                </label>
                                <select name="day" class="form-select" required>
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-clock me-2"></i>Start Time
                                        </label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-clock me-2"></i>End Time
                                        </label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gradient w-100">
                                <i class="fas fa-save me-2"></i>Save Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Current Schedules</h4>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover schedule-table">
                                <thead>
                                    <tr>
                                        <th>Laboratory</th>
                                        <th>Professor</th>
                                        <th>Subject</th>
                                        <th class="day-column">Day</th>
                                        <th class="time-column">Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($schedule = $schedules->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary badge-custom">
                                                Lab <?= htmlspecialchars($schedule['lab_number']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($schedule['professor_name']) ?></td>
                                        <td><?= htmlspecialchars($schedule['subject']) ?></td>
                                        <td><?= htmlspecialchars($schedule['day']) ?></td>
                                        <td>
                                            <?= date('h:i A', strtotime($schedule['start_time'])) ?> - 
                                            <?= date('h:i A', strtotime($schedule['end_time'])) ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="deleteSchedule(<?= $schedule['schedule_id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Form validation
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            const startTime = document.querySelector('input[name="start_time"]').value;
            const endTime = document.querySelector('input[name="end_time"]').value;
            
            if (startTime >= endTime) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Time',
                    text: 'End time must be after start time'
                });
            }
        });

        function deleteSchedule(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `delete_schedule.php?id=${id}`;
                }
            });
        }

        function printSchedules() {
            window.print();
        }

        function exportToExcel() {
            let table = document.querySelector('.schedule-table');
            let html = table.outerHTML;
            
            let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            let downloadLink = document.createElement("a");
            downloadLink.href = url;
            downloadLink.download = "lab_schedules.xls";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Show success/error messages
        <?php if(isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $_SESSION['success'] ?>',
                timer: 2000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= $_SESSION['error'] ?>'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
