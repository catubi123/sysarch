<?php
include('db.php');
include('admin_navbar.php');

// Process filter parameters
$where_conditions = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['student_id'])) $where_conditions[] = "s.id_number LIKE '%". mysqli_real_escape_string($con, $_POST['student_id']) . "%'";
    if (!empty($_POST['name'])) $where_conditions[] = "(u.fname LIKE '%". mysqli_real_escape_string($con, $_POST['name']) ."%' OR u.lname LIKE '%". mysqli_real_escape_string($con, $_POST['name']) ."%')";
    if (!empty($_POST['course'])) $where_conditions[] = "u.Course LIKE '%". mysqli_real_escape_string($con, $_POST['course']) ."%'";
    if (!empty($_POST['lab_room'])) $where_conditions[] = "s.sit_lab = '". mysqli_real_escape_string($con, $_POST['lab_room']) ."'";
    if (!empty($_POST['date'])) $where_conditions[] = "s.sit_date = '". mysqli_real_escape_string($con, $_POST['date']) ."'";
    if (!empty($_POST['purpose'])) $where_conditions[] = "s.sit_purpose LIKE '%". mysqli_real_escape_string($con, $_POST['purpose']) ."%'";
    if (!empty($_POST['status'])) $where_conditions[] = "s.status = '". mysqli_real_escape_string($con, $_POST['status']) ."'";
}

// Get unique courses for dropdown
$course_query = "SELECT DISTINCT Course FROM user WHERE Course IS NOT NULL ORDER BY Course";
$course_result = mysqli_query($con, $course_query);
$courses = [];
while($row = mysqli_fetch_assoc($course_result)) {
    $courses[] = $row['Course'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sit-in Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-chart-bar"></i> Sit-in Reports</h4>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="POST" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="student_id" placeholder="Student ID" value="<?php echo $_POST['student_id'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $_POST['name'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="course">
                                <option value="">Select Course</option>
                                <?php foreach($courses as $course): ?>
                                    <option value="<?php echo $course; ?>" <?php echo (isset($_POST['course']) && $_POST['course'] == $course) ? 'selected' : ''; ?>>
                                        <?php echo $course; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="lab_room">
                                <option value="">Select Lab Room</option>
                                <option value="524" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab1') ? 'selected' : ''; ?>>524</option>
                                <option value="526" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab2') ? 'selected' : ''; ?>>526</option>
                                <option value="528" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab3') ? 'selected' : ''; ?>>528</option>
                                <option value="530" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab3') ? 'selected' : ''; ?>>530</option>
                                <option value="542" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab3') ? 'selected' : ''; ?>>542</option>
                                <option value="544" <?php echo (isset($_POST['lab_room']) && $_POST['lab_room'] == 'Lab3') ? 'selected' : ''; ?>>544</option>                          
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="date" value="<?php echo $_POST['date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="purpose" placeholder="Purpose" value="<?php echo $_POST['purpose'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Select Status</option>
                                <option value="Present" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Present') ? 'Completed' : ''; ?>>Completed</option>
                                <option value="Absent" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Absent') ? 'Pending' : ''; ?>>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="generate_report.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <!-- Export toolbar -->
                <div id="exportToolbar"></div>

                <table id="reportTable" class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Lab Room</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT s.*, u.fname, u.lname, u.Course 
                                FROM student_sit_in s 
                                LEFT JOIN user u ON s.id_number = u.id";

                        if (!empty($where_conditions)) {
                            $query .= " WHERE " . implode(" AND ", $where_conditions);
                        }

                        $query .= " ORDER BY s.sit_date DESC, s.time_in DESC";
                        $result = mysqli_query($con, $query);
                        
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['id_number']}</td>
                                    <td>{$row['fname']} {$row['lname']}</td>
                                    <td>{$row['Course']}</td>
                                    <td>{$row['sit_lab']}</td>
                                    <td>{$row['sit_date']}</td>
                                    <td>{$row['time_in']}</td>
                                    <td>{$row['time_out']}</td>
                                    <td>{$row['sit_purpose']}</td>
                                    <td>{$row['status']}</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Required JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#reportTable').DataTable({
                dom: 'Brtip',
                buttons: [
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-primary',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        orientation: 'Potrait',
                        pageSize: 'LEGAL',
                        title: 'Laboratory Attendance Report',
                        exportOptions: { columns: ':visible' },
                        customize: function(doc) {
                            doc.content.splice(0, 0, {
                                text: [
                                    { text: 'UNIVERSITY OF CEBU\n', style: 'header' },
                                    { text: 'College of Computer Studies\n', style: 'subheader' },
                                    { text: 'Laboratory Attendance Report\n\n', style: 'title' }
                                ],
                                alignment: 'center',
                                margin: [0, 0, 0, 20]
                            });

                            doc.styles = {
                                header: { fontSize: 18, bold: true },
                                subheader: { fontSize: 14, bold: true },
                                title: { fontSize: 14, bold: true },
                                tableHeader: {
                                    fontSize: 12,
                                    bold: true,
                                    fillColor: '#0d6efd',
                                    color: 'white'
                                }
                            };
                            doc.defaultStyle.fontSize = 10;
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info text-white',
                        exportOptions: { columns: ':visible' }
                    }
                ],
                pageLength: 25,
                order: [[4, 'desc'], [5, 'desc']],
                searching: false
            });

            // Remove existing button handlers and use DataTables buttons directly
            $('.dt-buttons')
                .addClass('btn-group mb-3')
                .appendTo('#exportToolbar');
        });
    </script>
</body>
</html>
