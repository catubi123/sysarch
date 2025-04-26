<?php
include('db.php');
include('admin_navbar.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Reservations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 1rem;
        }
        .card-header {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }
        .status-pending { 
            color: #ffc107;
            font-weight: 500;
        }
        .status-approved { 
            color: #198754;
            font-weight: 500;
        }
        .status-rejected { 
            color: #dc3545;
            font-weight: 500;
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .dataTables_wrapper .dataTables_length select {
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            border-radius: 0.375rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid px-4 py-3">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0 text-white">
                    <i class="fas fa-calendar-check"></i> Laboratory Reservations
                </h4>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="POST" class="filter-section">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="student_id" placeholder="Student ID" value="<?php echo $_POST['student_id'] ?? ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="lab_room">
                                <option value="">Select Lab Room</option>
                                <option value="524">524</option>
                                <option value="526">526</option>
                                <option value="528">528</option>
                                <option value="530">530</option>
                                <option value="542">542</option>
                                <option value="544">544</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="date" value="<?php echo $_POST['date'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="view_reservations.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="reservationsTable" class="table table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Lab Room</th>
                                <th>Purpose</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reservationsTable').DataTable({
                pageLength: 10,
                order: [[4, 'desc'], [5, 'desc']],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    search: "Search reservations:",
                    emptyTable: "No reservations found"
                }
            });

            // Handle approve/reject buttons
            $('.approve-btn, .reject-btn').click(function() {
                const id = $(this).data('id');
                const status = $(this).hasClass('approve-btn') ? 'approved' : 'rejected';
                
                if(confirm('Are you sure you want to ' + status + ' this reservation?')) {
                    $.post('update_reservation_status.php', {
                        id: id,
                        status: status
                    }, function(response) {
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
