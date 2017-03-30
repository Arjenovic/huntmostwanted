<?php
// Create connection
    $mysqli = mysqli_connect("localhost", "root", "", "ownapi");

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }else{
        /* echo "We have a connection with the database. "; // DONT ADD TEXT HERE, HEADER HAS TO BE SENT FIRST */
    }
?>