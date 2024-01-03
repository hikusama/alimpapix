<?php
require_once 'includes/db_connect.php';



// Create tables


$tables_sql = file_get_contents("includes/siqwel/librarydb.sql");

if ($conn->multi_query($tables_sql)) {
    echo "Tables created successfully<br>";
} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}
