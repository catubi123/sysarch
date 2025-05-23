<?php
include('db.php');
include('admin_navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Student Information</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="add_student.php" class="btn btn-primary me-2">Add Students</a>
                        <button class="btn btn-danger" onclick="confirmResetSessions()">Reset All Session</button>
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>Year Level</th>
                                <th>Course</th>
                                <th>Remaining Session</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                               // This query is correct, using remaining_session
                               $query = "SELECT id, lname, fname, MName, Level, Course, remaining_session 
                                      FROM user WHERE role = 'user' ORDER BY lname ASC";
                            
                            $result = mysqli_query($con, $query);
                            if (!$result) {
                                echo "<tr><td colspan='6' class='text-center text-danger'>Error fetching data: " . mysqli_error($con) . "</td></tr>";
                            } else if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sessionClass = '';
                                    // Make sure we're using the correct column name
                                    $remaining = intval($row['remaining_session']);
                                    
                                    if ($remaining <= 5) {
                                        $sessionClass = 'text-danger fw-bold';
                                    } elseif ($remaining <= 10) {
                                        $sessionClass = 'text-warning fw-bold';
                                    }

                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['lname']}, {$row['fname']} {$row['MName']}</td>
                                            <td>{$row['Level']}</td>
                                            <td>{$row['Course']}</td>
                                            <td class='{$sessionClass}'>{$remaining}</td>
                                            <td>
                                                <button class='btn btn-link text-warning p-0 me-2' onclick='location.href=\"edit_student.php?id={$row['id']}\"'>
                                                    <i class='fas fa-edit fa-lg' title='Edit Student'></i>
                                                </button>
                                                <button class='btn btn-link text-danger p-0 me-2' onclick='confirmDelete({$row['id']})'>
                                                    <i class='fas fa-trash-alt fa-lg' title='Delete Student'></i>
                                                </button>
                                                <button class='btn btn-link text-info p-0' onclick='confirmResetSingleStudent({$row['id']})'>
                                                    <i class='fas fa-sync-alt fa-lg' title='Reset Session'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No students found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This student record will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send delete request
                fetch('delete_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}&delete=true`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('success')) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Student record has been deleted.',
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to delete student record: ' + data,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong with the request.',
                        icon: 'error'
                    });
                });
            }
        });
    }

    function confirmResetSessions() {
        Swal.fire({
            title: 'Reset All Sessions?',
            text: 'This will reset remaining sessions to 30 for ALL students. This cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reset all!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Resetting all sessions...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('process_reset_session.php')
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'All student sessions have been reset successfully.',
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to reset sessions. Please try again.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong with the request.',
                            icon: 'error'
                        });
                    });
            }
        });
    }

    function confirmResetSingleStudent(id) {
        // First check if student has maximum sessions
        fetch('check_session.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.hasMaxSession) {
                Swal.fire({
                    title: 'Warning!',
                    text: 'This student already has maximum sessions (30)!',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                });
                return;
            }
            
            Swal.fire({
                title: 'Reset Student Session?',
                text: 'This will reset the remaining sessions to 30 for this student. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Resetting student session...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('process_reset_session.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${id}&single=true`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Student session has been reset successfully.',
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to reset session. Please try again.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong with the request.',
                            icon: 'error'
                        });
                    });
                }
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to check session status.',
                icon: 'error'
            });
        });
    }
    </script>
</body>
</html>
