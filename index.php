<?php
// Redirect to login page

require_once 'includes/createdb.php';
require_once 'includes/db_connect.php';
require_once 'includes/create_table.php';



header("Location: login.php");
exit();
?>
