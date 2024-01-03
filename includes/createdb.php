<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "librarydb";

    // Create connection
    $mysqli = new mysqli($servername, $username, $password);

    // Create the database if it doesn't exist
    $query = "CREATE DATABASE IF NOT EXISTS $db_name";
    if ($mysqli->query($query) === TRUE) {
        //echo "Database created successfully";
    } else {
        echo "Error creating database: " . $mysqli->error;
    }

    // Close connection
    $mysqli->close();

?>
