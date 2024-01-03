<?php
// Include necessary files
require_once('includes/db_connect.php');

// Get the database connection
$conn = connectToDatabase();

session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_role'])) {
    // Redirect to the login page or perform other authentication actions
    header("Location: login.php");
    exit();
}

// Fetch borrowed books from the database
$sql = "SELECT * FROM borrowedbooks";

$result = $conn->query($sql);

// Check if there are borrowed books
if ($result->num_rows > 0) {



    ?>



    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <style>
        body {
            background-color: #111927;
            color: #333;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: #007bff;
            margin-top: 10px;
            display: inline-block;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            echo '<table border="1">';
    echo '<tr><th>BorrowID</th><th>UserID</th><th>BookID</th><th>BorrowedDate</th><th>ReturnDate</th></tr>';

    // Output data of each borrowed book
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['BorrowID'] . '</td>';
        echo '<td>' . $row['UserID'] . '</td>';
        echo '<td>' . $row['BookID'] . '</td>';
        echo '<td>' . $row['BorrowedDate'] . '</td>';
        echo '<td>' . $row['ReturnDate'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    // Display "Edit" and "Delete" links for admin
    if ($_SESSION['user_role'] === 'Admin') {
        echo '<a href="edit_borrowed.php">Edit Return Date</a>  |';
        echo '  <a href="delete_borrowed.php">Delete Borrowed Book</a>';
    }
        ?>
    </div>
</body>
</html>
<?php


} else {
    echo 'No borrowed books.';
}

// Close the connection
$conn->close();
?>
