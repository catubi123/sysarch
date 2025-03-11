<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
    <link rel="stylesheet" href="w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></head>
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

    <div class="container mt-4" style="max-width: 500px;">
        <div class="card p-3">
            <h2 class="text-center">Reservation</h2>
            <form method="POST" action="process_reservation.php">
                <div class="mb-2">
                    <label for="idNumber" class="form-label">ID Number:</label>
                    <input type="text" class="form-control" id="idNumber" name="idNumber" value="22677116" readonly>
                </div>

                <div class="mb-2">
                    <label for="studentName" class="form-label">Student Name:</label>
                    <input type="text" class="form-control" id="studentName" name="studentName" value="Mark Dave Catubig" readonly>
                </div>

                <div class="mb-2">
                    <label for="purpose" class="form-label">Purpose:</label>
                    <input type="text" class="form-control" id="purpose" name="purpose" value="C Programming">
                </div>

                <div class="mb-2">
                    <label for="lab" class="form-label">Lab:</label>
                    <input type="text" class="form-control" id="lab" name="lab" value="524">
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>

                <div class="mb-2">
                    <label for="timeIn" class="form-label">Time In:</label>
                    <input type="time" class="form-control" id="timeIn" name="timeIn">
                </div>

                <div class="mb-2">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>

                <div class="mb-2">
                    <label for="remainingSession" class="form-label">Remaining Session:</label>
                    <input type="text" class="form-control" id="remainingSession" name="remainingSession" value="30" readonly>
                </div>

                <button type="button" class="btn btn-primary w-100">Reserve</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>