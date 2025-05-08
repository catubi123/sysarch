<?php
session_start();
include('db.php');
$conn = openConnection();

$sql = "SELECT s.*, u.fname, u.lname, u.course, u.level 
        FROM student_sit_in s
        JOIN user u ON s.id_number = u.id
        WHERE s.status = 'Active'
        ORDER BY s.sit_date DESC, s.time_in DESC";
$result = $conn->query($sql);

// Get all students for direct sit-in form
$students_sql = "SELECT id, CONCAT(fname, ' ', lname, ' (', id, ')') as student_name FROM user ORDER BY fname";
$students_result = $conn->query($students_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sit-ins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 20px;
        }
        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body class="bg-white">
    <?php include 'admin_navbar.php' ?>
    
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Laboratory Sit-in Management</h3>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="sitInTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab">
                            <i class="fas fa-users"></i> Current Sit-ins
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="direct-tab" data-bs-toggle="tab" data-bs-target="#direct" type="button" role="tab">
                            <i class="fas fa-plus-circle"></i> Direct Sit-in
                        </button>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="sitInTabContent">
                    <!-- Current Sit-ins Tab -->
                    <div class="tab-pane fade show active" id="current" role="tabpanel">
                        <?php if(isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Number</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Year Level</th>
                                        <th>Purpose</th>
                                        <th>Laboratory</th>
                                        <th>Time In</th>
                                        <th>Date</th>
                                        <th>Action</th>
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
                                        <td><?php echo htmlspecialchars($row['sit_date']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <form action="timeout_sitin.php" method="POST" class="me-1">
                                                    <input type="hidden" name="sit_id" value="<?php echo $row['sit_id']; ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm">Time Out</button>
                                                </form>
                                                <form action="add_point.php" method="POST">
                                                    <input type="hidden" name="sit_id" value="<?php echo $row['sit_id']; ?>">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['id_number']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-plus"></i> Add Point
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Direct Sit-in Tab -->
                    <div class="tab-pane fade" id="direct" role="tabpanel">
                        <form action="process_direct_sitin.php" method="POST" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student</label>
                                    <select class="form-select select2" name="student_id" required>
                                        <option value="">Select Student</option>
                                        <?php while($student = $students_result->fetch_assoc()): ?>
                                            <option value="<?php echo $student['id']; ?>">
                                                <?php echo htmlspecialchars($student['student_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a student</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Laboratory</label>
                                    <select class="form-select" name="laboratory" required>
                                        <option value="">Select Laboratory</option>
                                        <option value="524">524</option>
                                        <option value="526">526</option>
                                        <option value="528">528</option>
                                        <option value="530">530</option>
                                        <option value="542">542</option>
                                        <option value="544">544</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a laboratory</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Purpose</label>
                                    <select class="form-select" name="purpose" required>
                                        <option value="">Select Purpose</option>
                                        <option value="ASP.net">ASP.NET Programming</option>
                                        <option value="C#">C# Programming</option>
                                        <option value="C++">C++ Programming</option>
                                        <option value="Database">Database</option>
                                        <option value="Java">Java Programming</option>
                                        <option value="PHP">PHP Programming</option>
                                        <option value="Python">Python Programming</option>
                                        <option value="Web">Web Development</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a purpose</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">PC Number</label>
                                    <input type="number" class="form-control" name="pc_number" min="1" max="50" required>
                                    <div class="invalid-feedback">Please enter a valid PC number (1-50)</div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Submit Direct Sit-in
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select a student'
            });

            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide success messages
            setTimeout(function() {
                $('.alert-success').fadeOut('slow');
            }, 3000);
        });

        // Add DataTable initialization if needed
        if ($.fn.DataTable) {
            $('.table').DataTable({
                responsive: true,
                order: [[7, 'desc'], [6, 'desc']], // Sort by date and time
                language: {
                    search: "Search sit-ins:"
                }
            });
        }
    </script>
</body>
</html>
