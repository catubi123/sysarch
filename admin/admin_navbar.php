<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add Bootstrap and FontAwesome before other styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            margin: 0; 
            padding: 0; 
            min-height: 100vh;
        }
        .navbar { 
            margin: 0; 
            padding: 1rem;
        }
        .navbar-brand {
            padding: 0;
        }
        .dropdown-menu { 
            margin-top: 0; 
            border-radius: 0.5rem;
        }
        .dropdown-item:hover { 
            background-color: #0d6efd; 
            color: white; 
        }
        .dropdown-item i { 
            width: 20px; 
            text-align: center; 
            margin-right: 10px; 
        }
        .notification-badge { 
            font-size: 0.6rem; 
            padding: 0.25rem 0.4rem; 
        }
        @media (min-width: 992px) {
            .navbar .dropdown-menu { 
                display: none; 
                margin-top: 0;
            }
            .navbar .nav-item:hover .dropdown-menu { 
                display: block; 
            }
        }
    </style>
</head>
<body class="d-flex flex-column">
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
                
                <!-- Lab dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="labDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-laptop-code"></i> Lab
                    </a>
                    <ul class="dropdown-menu shadow" aria-labelledby="labDropdown">
                        <li><a class="dropdown-item" href="lab_management.php"><i class="fas fa-laptop"></i> Lab Management</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="manage_materials.php"><i class="fas fa-book"></i> Lab Materials</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="lab_schedules.php"><i class="fas fa-calendar-alt"></i> Lab Schedules</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link text-white" href="sit-in.php">Sit-in</a></li>

                <!-- Views dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="viewsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Views
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="viewsDropdown">
                        <li><a class="dropdown-item" href="sit-in-records.php">View Sit-in Records</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="student_information.php">View List of Students</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link text-white" href="generate_report.php">Generate Reports</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="view_reservations.php">Reservation</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="view_feedback.php">Feedbacks</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="top_users.php">Top Users</a></li>
                
                <!-- Notifications Bell -->
                <li class="nav-item">
                    <a class="nav-link text-white position-relative" href="view_reservations.php?status=pending">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            <span id="notificationCount">0</span>
                        </span>
                    </a>
                </li>

                <!-- Log Out Button -->
                <a href="logout.php" class="btn btn-danger ms-lg-3">Log Out</a>
            </ul>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize dropdowns and notifications
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    const dropdowns = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    dropdowns.map(function (dropdownToggle) {
        return new bootstrap.Dropdown(dropdownToggle, {
            offset: [0, 8]
        })
    })

    // Handle hover events for desktop
    if (window.innerWidth >= 992) {
        document.querySelectorAll('.navbar .nav-item.dropdown').forEach(function(item) {
            item.addEventListener('mouseenter', function(e) {
                let dropdown = this.querySelector('.dropdown-toggle')
                let instance = bootstrap.Dropdown.getInstance(dropdown)
                if (instance) instance.show()
            })
            
            item.addEventListener('mouseleave', function(e) {
                let dropdown = this.querySelector('.dropdown-toggle')
                let instance = bootstrap.Dropdown.getInstance(dropdown)
                if (instance) instance.hide()
            })
        })
    }

    // Check notifications on load and every 30 seconds
    checkNewReservations();
    setInterval(checkNewReservations, 30000);
});

// Prevent dropdown close on click inside
document.querySelectorAll('.dropdown-menu').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

// Simplified notification check
function checkNewReservations() {
    fetch('check_new_reservations.php')
        .then(response => response.json())
        .then(data => {
            const count = Array.isArray(data) ? data.length : 0;
            document.getElementById('notificationCount').textContent = count;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('notificationCount').textContent = '!';
        });
}

// Helper functions
function getNotificationIcon(type) {
    const icons = {
        'reservation': 'fa-calendar-check',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle',
        'success': 'fa-check-circle',
        'error': 'fa-times-circle'
    };
    const iconClass = icons[type] || 'fa-bell';
    return `<i class="fas ${iconClass} text-primary"></i>`;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>
</body>
</html>