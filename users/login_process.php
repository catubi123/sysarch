<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id, fname, lname FROM user WHERE username = ? AND password = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $user['id'];
        $_SESSION['studentName'] = $user['fname'] . ' ' . $user['lname'];
        header("Location: home.php");
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.php");
    }
    exit();
}
?>
