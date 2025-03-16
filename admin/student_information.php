<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-gradient" style="background: linear-gradient(135deg, #4a69bd, #8e44ad);">
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
                    <li class="nav-item"><a class="nav-link text-white" href="admin_Dashboard.php">Home</a></li>
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
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header text-blue w3-padding text-center bg-gradient" style="background: linear-gradient(135deg, #4a69bd, #8e44ad);">
                <h3 class="fw-bold">STUDENT INFORMATION</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <button class="btn btn-primary me-2">Add Students</button>
                        <button class="btn btn-danger">Reset All Session</button>
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Search...">
                    </div>
                </div>
                
                <table class="table table-striped table-hover">
                    <thead class="text-white" style="background: linear-gradient(135deg, #4a69bd, #8e44ad);">
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
                        <tr>
                            <td colspan="6" class="text-center text-muted">No data available</td>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between">
                    <span>Showing 0 to 0 of 0 entries</span>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link">&laquo;</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link">&raquo;</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
