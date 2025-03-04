<?php
     $con = mysqli_connect("localhost","root","","users");
     if (!$con) {
         die("Connection failed: " . mysqli_connect_error());
     }
?>