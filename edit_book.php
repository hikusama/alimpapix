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

// Initialize variables
$bookID = $title = $author = $genre = $publishedYear = '';
$success = $error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bookID = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $publishedYear = $_POST['published_year'];

    // Validate form data (you can add more validation as needed)
    if (empty($bookID) || empty($title) || empty($author) || empty($genre) || empty($publishedYear)) {
        $error = "All fields are required.";
    } else {
        // Update the book in the database
        $conn = connectToDatabase();
        $sql = "UPDATE books SET Title = ?, Author = ?, Genre = ?, PublishedYear = ? WHERE BookID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $author, $genre, $publishedYear, $bookID);

        if ($stmt->execute()) {
            // Check the number of affected rows
            $affected_rows = $stmt->affected_rows;

            if ($affected_rows > 0) {
                $success = "Book updated successfully!";
            } else {
                $error = "No changes made. The book details may already be the same.";
            }
        } else {
            // Debugging: Output the executed SQL statement
            echo "<p>SQL: $sql</p>";

            // Debugging: Output any error messages
            echo "<p>Error: " . $stmt->error . "</p>";

            $error = "Error updating book: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
} elseif (isset($_GET['id'])) {
    // Fetch the details of the book from the database
    $bookID = $_GET['id'];
    $conn = connectToDatabase();
    $sql = "SELECT * FROM books WHERE BookID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the book exists
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $title = $book['Title'];
        $author = $book['Author'];
        $genre = $book['Genre'];
        $publishedYear = $book['PublishedYear'];
    } else {
        // If the book does not exist, show an error message
        $error = "No book found with ID: $bookID";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Book</h2>

        <?php
        // Display success or error message
        if ($success) {
            echo '<p class="success">' . $success . '</p>';
        } elseif ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Edit Book Form -->
        <form method="post" action="">
            <label for="book_id">Book ID:</label>
            <input type="text" name="book_id" value="<?php echo $bookID; ?>" required>

            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $title; ?>" required>

            <label for="author">Author:</label>
            <input type="text" name="author" value="<?php echo $author; ?>" required>

            <label for="genre">Genre:</label>
            <input type="text" name="genre" value="<?php echo $genre; ?>" required>

            <label for="published_year">Published Year:</label>
            <input type="text" name="published_year" value="<?php echo $publishedYear; ?>" required>

            <button type="submit">Update Book</button>
        </form>
        <a href="admin_home.php">Back to Admin Home</a>
    </div>
</body>
</html>
