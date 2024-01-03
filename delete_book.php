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
$bookID = $title = '';
$success = $error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bookID = $_POST['book_id'];
    $title = $_POST['title'];

    // Validate form data
    if (empty($bookID) || empty($title)) {
        $error = "All fields are required.";
    } else {
        // Delete the book from the database
        $conn = connectToDatabase();
        $sql = "DELETE FROM books WHERE BookID = ? AND Title = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $bookID, $title);

        if ($stmt->execute()) {
            // Check the number of affected rows
            $affected_rows = $stmt->affected_rows;

            if ($affected_rows > 0) {
                $success = "Book deleted successfully!";
            } else {
                $error = "No changes made. The book details may not match.";
            }
        } else {
            // Debugging: Output the executed SQL statement
            echo "<p>SQL: $sql</p>";

            // Debugging: Output any error messages
            echo "<p>Error: " . $stmt->error . "</p>";

            $error = "Error deleting book: " . $stmt->error;
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
    <title>Delete Book</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Delete Book</h2>

        <?php
        // Display success or error message
        if ($success) {
            echo '<p class="success">' . $success . '</p>';
        } elseif ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Delete Book Form -->
        <form method="post" action="">
            <label for="book_id">Book ID:</label>
            <input type="text" name="book_id" value="<?php echo $bookID; ?>" required>

            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $title; ?>" required>

            <button type="submit">Delete Book</button>
        </form>
        <a href="admin_home.php">Back to Admin Home</a>
    </div>
</body>
</html>
