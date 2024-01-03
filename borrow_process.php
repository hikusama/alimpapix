<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Borrow Book</h2>

    <?php
    require_once('includes/db_connect.php');

    // Start or resume session
    session_start();

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
        // If not logged in as admin, redirect to login page
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get data from the form
        $borrow_id = uniqid();  // Generate a unique BorrowID
        $user_id = $_POST['user_id'];
        $book_id = $_POST['book_id'];
        $borrow_date = $_POST['borrow_date'];
        $return_date = $_POST['return_date'];

        // Insert data into BorrowedBooks table
        $insert_query = "INSERT INTO BorrowedBooks (BorrowID, UserID, BookID, BorrowedDate, ReturnDate)
                         VALUES ('$borrow_id', '$user_id', '$book_id', '$borrow_date', '$return_date')";

        // Perform the query
        if ($conn->query($insert_query) === true) {
            echo '<p class="message">Book borrowed successfully.</p>';
            echo '<form method="post" action="home.php">';
            echo '<button type="submit">Return</button>';
            echo '</form>';
        } else {
            echo '<p class="message">Error: ' . $conn->error . '</p>';
        }
    } else {
        echo '<p class="message">Invalid request.</p>';
    }

    // Close the database connection
    $conn->close();
    ?>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>
