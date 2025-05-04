<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 1rem;
        }
        .header-gradient {
            background: linear-gradient(45deg, #0d6efd, #0a58ca);
            color: white;
            padding: 2rem;
            border-radius: 1rem 1rem 0 0;
            margin: -1rem -1rem 1.5rem -1rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-control:read-only {
            background-color: #f8f9fa;
        }
        .required::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }
        .time-badge {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="home.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <div class="navbar-nav ms-auto">
            <a href="home.php" class="nav-link text-white">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="#" class="nav-link text-white">
                <i class="fas fa-history"></i> History
            </a>
            <a href="edit.php" class="nav-link text-white">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="reservation.php" class="nav-link text-white">
                <i class="fas fa-calendar-check"></i> Reservation
            </a>
            <a href="index.php" class="nav-link text-white bg-danger rounded-pill px-3">Log out</a>
        </div>
    </div>
</nav>

<div class="container mt-4" style="max-width: 600px;">
    <div class="card p-3">
        <div class="header-gradient">
            <h2 class="text-center mb-0">
                <i class="fas fa-calendar-plus"></i> Laboratory Reservation
            </h2>
        </div>

        <form method="POST" action="process_reservation.php" id="reservationForm" class="needs-validation" novalidate>
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="idNumber" name="idNumber" value="22677116" readonly>
                        <label><i class="fas fa-id-card"></i> ID Number</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" id="studentName" name="studentName" value="Mark Dave Catubig" readonly>
                        <label><i class="fas fa-user"></i> Student Name</label>
                    </div>

                    <div class="form-floating">
                        <select class="form-select" id="purpose" name="purpose" required>
                            <option value="">Select Purpose</option>
                            <option value="ASP.net">ASP.NET Programming</option>
                            <option value="C#">C# Programming</option>
                            <option value="C++">C++ Programming</option>
                            <option value="C">C Programming</option>
                            <option value="Database">Database</option>
                            <option value="DGILOG">Digital Logic & Design</option>
                            <option value="Embedded">Embedded System & IoT</option>
                            <option value="Java">Java Programming</option>
                            <option value="Others">Others</option>
                            <option value="PHP">PHP Programming</option>
                            <option value="Python">Python Programming</option>
                            <option value="System">System Architecture and Integration</option>
                            <option value="Web">Web Design & Development</option>
                        </select>
                        <label class="required"><i class="fas fa-tasks"></i> Purpose</label>
                        <div class="invalid-feedback">Please select a purpose</div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="lab" name="lab" required>
                            <option value="">Select Laboratory</option>
                            <option value="524">524</option>
                            <option value="526">526</option>
                            <option value="528">528</option>
                            <option value="530">530</option>
                            <option value="542">542</option>
                            <option value="544">544</option>
                        </select>
                        <label class="required"><i class="fas fa-laptop"></i> Laboratory</label>
                        <div class="invalid-feedback">Please select a laboratory</div>
                    </div>

                    <div class="form-floating">
                        <input type="date" class="form-control" id="date" name="date" required>
                        <label class="required"><i class="fas fa-calendar"></i> Date</label>
                        <div class="invalid-feedback">Please select a date</div>
                    </div>

                    <div class="form-floating">
                        <input type="time" class="form-control" id="timeIn" name="timeIn" required>
                        <label class="required"><i class="fas fa-clock"></i> Time In</label>
                        <div class="invalid-feedback">Please select a time between 8:00 AM and 5:00 PM</div>
                    </div>
                </div>

                <!-- Full Width Elements -->
                <div class="col-12 position-relative">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="remainingSession" name="remainingSession" value="30" readonly>
                        <label><i class="fas fa-hourglass-half"></i> Session Duration</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Important Information:
                        <ul class="mb-0">
                            <li>Laboratory hours: 8:00 AM - 5:00 PM</li>
                            <li>Fields marked with <span class="text-danger">*</span> are required</li>
                        </ul>
                    </div>
                </div>

                <div class="col-12 d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check"></i> Submit Reservation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set minimum date to today
    document.getElementById('date').min = new Date().toISOString().split('T')[0];

    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Time validation
    document.getElementById('timeIn').addEventListener('change', function() {
        const time = this.value.split(':');
        const hour = parseInt(time[0]);
        if (hour < 8 || hour >= 17) {
            this.setCustomValidity('Please select a time between 8:00 AM and 5:00 PM');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
</body>
</