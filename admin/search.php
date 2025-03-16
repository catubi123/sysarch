<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Ensure the navbar stays at the top */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1030; /* Ensures it's above other elements */
    }

    /* Add padding to the body to prevent overlap with navbar */
    body {
      padding-top: 80px; /* Adjust based on the navbar height */
      background: linear-gradient(135deg, #4a69bd, #8e44ad);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .search-box {
      width: 300px; /* Adjust as needed */
    }
  </style>
</head>
<body class="bg-gradient">
<div class="navbar navbar-expand-lg navbar-dark bg-primary rounded-0">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fas fa-bars me-2"></i>
            <h2 class="mb-0 text-white">Admin</h2>
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
<div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 80px);">
  <div class="search-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Search Student</h2>
      <button type="button" class="btn-close" aria-label="Close" onclick="closeSearch()"></button>
    </div>
    <div class="mb-3">
      <input type="text" class="form-control" id="searchInput" placeholder="Search...">
    </div>
    <button type="button" class="btn btn-primary w-100" onclick="performSearch()">Search</button>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function performSearch() {
  var searchTerm = document.getElementById("searchInput").value;
  // Here you would typically send the searchTerm to your server-side PHP script
  // using AJAX and then display the results.
  alert("Searching for: " + searchTerm); // Placeholder for actual search logic
}

function closeSearch() {
  // Redirect to admin_Dashboard.php
  window.location.href = "admin_Dashboard.php";
}
</script>

</body>
</html>
