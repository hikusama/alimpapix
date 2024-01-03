<?php
// Include necessary files
require_once('includes/db_connect.php');
require_once('includes/functions.php');

// Start or resume session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    // If not logged in as admin, redirect to login page
    header("Location: login.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publishedYear = $_POST['published_year'];

    // Validate form data (you can add more validation as needed)
    if (empty($title) || empty($author) || empty($publishedYear)) {
        $error = "All fields are required.";
    } else {
        // Insert the new book into the database
        $conn = connectToDatabase();
    $sql = "INSERT INTO books (Title, Author, Genre, PublishedYear) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $author, $genre, $publishedYear);

    if ($stmt->execute()) {
        $success = "Book added successfully!";
    } else {
        $error = "Error adding book: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #111927;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .success {
            color: #4caf50;
            margin-top: 20px;
            text-align: center;
        }

        .error {
            color: #f44336;
            margin-top: 20px;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add a New Book</h2>

        <?php
        // Display success or error message
        if (isset($success)) {
            echo '<p class="success">' . $success . '</p>';
        } elseif (isset($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Add Book Form -->
        <form method="post" action="">
            <label for="title">Title:</label>
            <input type="text" name="title" required>

            <label for="author">Author:</label>
            <input type="text" name="author" required>

            <label for="genre">Genre:</label>
            <input type="text" name="genre" required>

            <label for="published_year">Published Year:</label>
            <input type="text" name="published_year" required>

            <button type="submit">Add Book</button>
        </form>

        <a href="admin_home.php">Back to Admin Home</a>
    </div>
</body>

</html>
