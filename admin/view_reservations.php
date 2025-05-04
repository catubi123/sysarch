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
                            $where_conditions = [];
                            $params = [];
                            $types = "";

                            if (!empty($_POST['student_id'])) {
                                $where_conditions[] = "r.id_number = ?";
                                $params[] = $_POST['student_id'];
                                $types .= "s";
                            }
                            if (!empty($_POST['lab_room'])) {
                                $where_conditions[] = "r.lab = ?";
                                $params[] = $_POST['lab_room'];
                                $types .= "s";
                            }
                            if (!empty($_POST['date'])) {
                                $where_conditions[] = "r.reservation_date = ?";
                                $params[] = $_POST['date'];
                                $types .= "s";
                            }
                            if (!empty($_POST['status'])) {
                                $where_conditions[] = "r.status = ?";
                                $params[] = $_POST['status'];
                                $types .= "s";
                            }

                            $query = "SELECT r.*, u.fname, u.lname 
                                    FROM reservation r 
                                    LEFT JOIN user u ON r.id_number = u.id";

                            if (!empty($where_conditions)) {
                                $query .= " WHERE " . implode(" AND ", $where_conditions);
                            }

                            $query .= " ORDER BY r.reservation_date DESC, r.reservation_time DESC";

                            $stmt = $con->prepare($query);
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) {
                                $status_class = match($row['status']) {
                                    'pending' => 'status-pending',
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    default => ''
                                };

                                echo "<tr>
                                        <td>{$row['id_number']}</td>
                                        <td>{$row['fname']} {$row['lname']}</td>
                                        <td>{$row['lab']}</td>
                                        <td>{$row['purpose']}</td>
                                        <td>{$row['reservation_date']}</td>
                                        <td>{$row['reservation_time']}</td>
                                        <td class='{$status_class}'>{$row['status']}</td>
                                        <td class='action-buttons'>";
                                
                                if ($row['status'] === 'pending') {
                                    echo "<button class='btn btn-success btn-sm approve-btn' data-id='{$row['reservation_id']}'>
                                            <i class='fas fa-check'></i> Approve
                                          </button>
                                          <button class='btn btn-danger btn-sm reject-btn' data-id='{$row['reservation_id']}'>
                                            <i class='fas fa-times'></i> Reject
                                          </button>";
                                }
                                
                                echo "</td></tr>";
                            }
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                const button = $(this);
                const id = button.data('id');
                const status = button.hasClass('approve-btn') ? 'approved' : 'rejected';
                const actionText = status === 'approved' ? 'approve' : 'reject';
                
                Swal.fire({
                    title: `Confirm ${actionText}?`,
                    text: `Are you sure you want to ${actionText} this reservation?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: status === 'approved' ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Yes, ${actionText} it!`,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.post('update_reservation_status.php', {
                            id: id,
                            status: status
                        }).catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value.includes('success')) {
                            Swal.fire({
                                title: 'Success!',
                                text: status === 'approved' ? 
                                    'Reservation approved and sit-in entry created!' : 
                                    'Reservation has been rejected',
                                icon: 'success',
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: result.value || 'Something went wrong',
                                icon: 'error'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
