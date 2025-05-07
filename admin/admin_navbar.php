<div class="navbar navbar-expand-lg navbar-dark bg-primary rounded p-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <h2 class="mb-0 text-white"> Admin</h2>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="admin_Dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="lab_management.php">Lab</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="sit-in.php">Sit-in</a></li>

                <!-- Add Lab Materials Management Link -->
                <li class="nav-item"><a class="nav-link text-white" href="manage_materials.php"><i class="fas fa-book"></i> Lab Materials</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="viewsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Views
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="viewsDropdown">
                        <li><a class="dropdown-item" href="sit-in-records.php">View Sit-in Records</a></li>
                        <li><a class="dropdown-item" href="student_information.php">View List of Students</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="generate_report.php">Generate Reports</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="view_reservations.php">Reservation</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="view_feedback.php">   </i> feedbacks</a></li>
                <!-- Add new Top Users link -->
                <li class="nav-item"><a class="nav-link text-white" href="top_users.php">Top Users</a></li>
            </ul>
            <!-- Log Out Button -->
                <a href="../users/index.php" class="btn btn-danger ms-lg-3">Log Out</a>


        </div>
    </div>
</div>