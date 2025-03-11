<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-primary d-flex align-items-center min-vh-100">
    <div class="card shadow-lg p-4 rounded-4 mx-auto" style="max-width: 400px;">
        <h2 class="text-center mb-3 text-primary">Registration</h2>
        <form method="post">
            <label class="form-label">IDNO</label>
            <input class="form-control" type="number" name="id" required>

            <label class="form-label">Lastname</label>
            <input class="form-control" type="text" name="lname" required>

            <label class="form-label">Firstname</label>
            <input class="form-control" type="text" name="fname" required>

            <label class="form-label">MiddleName</label>
            <input class="form-control" type="text" name="MName">

            <label class="form-label">Course</label>
            <select class="form-select" name="Course" required>
                <option value=""></option>
                <option value="BSED">BSED</option>
                <option value="BSIT">BSIT</option>
                <option value="BSCPE">BSCPE</option>
                <option value="BSCRIM">BSCRIM</option>
                <option value="BSCA">BSCA</option>
                <option value="BSCS">BSCS</option>
                <option value="BPED">BPED</option>
            </select>

            <label class="form-label">Yr/Level</label>
            <select class="form-select" name="Level" required>
                <option value=""></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>

            <label class="form-label">Username</label>
            <input class="form-control" type="text" name="username" required>

            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>

            <div class="d-grid">
                <button class="btn btn-primary mt-3">Register</button>
            </div>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="index.php" class="text-decoration-none">Log in</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>
