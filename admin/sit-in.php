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
        .computer-grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin-top: 10px;
        }

        .computer-icon {
            aspect-ratio: 1;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: white;
            padding: 5px;
        }

        .computer-icon:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }

        .computer-icon.selected {
            border-color: #0d6efd;
            background-color: #e7f1ff;
        }

        .computer-icon.unavailable {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }

        .pc-number {
            font-size: 12px;
            margin-top: 5px;
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
                                    <select class="form-select" name="laboratory" id="laboratorySelect" required onchange="loadPCs(this.value)">
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

                                <div class="col-md-12">
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

                                <!-- PC Grid Container -->
                                <div class="col-12">
                                    <label class="form-label">Select PC:</label>
                                    <input type="hidden" name="pc_number" id="selectedPC" required>
                                    <div id="pcGrid" class="computer-grid-container">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-desktop fa-3x mb-2"></i>
                                            <p>Please select a laboratory to view available PCs</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
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
        });

        function loadPCs(labNumber) {
            const pcGrid = document.getElementById('pcGrid');
            if (!labNumber) {
                pcGrid.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-desktop fa-3x mb-2"></i>
                        <p>Please select a laboratory to view available PCs</p>
                    </div>`;
                return;
            }

            pcGrid.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Loading PCs...</p>
                </div>`;

            fetch(`get_pc_status.php?lab=${labNumber}`)
                .then(response => response.json())
                .then(data => {
                    pcGrid.innerHTML = '';
                    for (let i = 1; i <= 50; i++) {
                        const pc = data.pcs?.find(p => p.number === i) || { number: i, is_active: true };
                        const div = document.createElement('div');
                        div.className = `computer-icon ${!pc.is_active ? 'unavailable' : ''}`;
                        div.innerHTML = `
                            <i class="fas fa-desktop"></i>
                            <span class="pc-number">PC ${String(i).padStart(2, '0')}</span>`;
                        
                        if (pc.is_active) {
                            div.onclick = () => selectPC(div, i);
                        }
                        pcGrid.appendChild(div);
                    }
                })
                .catch(error => {
                    pcGrid.innerHTML = `
                        <div class="alert alert-danger">
                            Error loading PCs. Please try again.
                        </div>`;
                    console.error('Error:', error);
                });
        }

        function selectPC(element, pcNumber) {
            if (element.classList.contains('unavailable')) return;
            
            document.querySelectorAll('.computer-icon').forEach(pc => 
                pc.classList.remove('selected'));
            
            element.classList.add('selected');
            document.getElementById('selectedPC').value = pcNumber;

            // Show selection feedback
            Swal.fire({
                title: 'PC Selected',
                text: `You selected PC ${String(pcNumber).padStart(2, '0')}`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity() || !document.getElementById('selectedPC').value) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (!document.getElementById('selectedPC').value) {
                        alert('Please select a PC');
                    }
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>
</html>
