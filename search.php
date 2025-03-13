<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Custom styles to center the modal-like box */
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .search-box {
      width: 300px; /* Or adjust as needed */
    }
  </style>
</head>
<body>

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