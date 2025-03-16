<?php
$con = mysqli_connect("localhost", "root", "", "users");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}




function openConnection() {

    $servername = "localhost";

    $username = "root";

    $password = "";

    $dbname = "users";



    // Create connection

    $conn = new mysqli($servername, $username, $password, $dbname);



    // Check connection

    if ($conn->connect_error) {

        die("Connection failed: " . $conn->connect_error);

    }



    return $conn;

}



function closeConnection($conn) {

    $conn->close();

}

?>
