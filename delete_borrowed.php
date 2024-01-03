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
$borrowedId = $success = $error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $borrowedId = $_POST['borrowed_id'];

    // Validate form data
    if (empty($borrowedId)) {
        $error = "Borrowed ID is required.";
    } else {
        // Delete the borrowed book from the database
        $conn = connectToDatabase();
        $sql = "DELETE FROM BorrowedBooks WHERE BorrowID = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $borrowedId);

            if ($stmt->execute()) {
                // Check the number of affected rows
                $affectedRows = $stmt->affected_rows;

                if ($affectedRows > 0) {
                    $success = "Borrowed book deleted successfully!";
                } else {
                    $error = "No borrowed book found with ID: $borrowedId";
                }
            } else {
                // Debugging: Output any error messages
                $error = "Error deleting borrowed book: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle error in preparing the statement
            $error = "Error preparing statement: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Borrowed Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #555;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        .error {
            color: #ff0000;
            margin-bottom: 16px;
        }

        .success {
            color: #008000;
            margin-bottom: 16px;
        }

        a {
            display: block;
            margin-top: 16px;
            text-align: center;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Borrowed Book</h2>

        <?php
        // Display success or error message
        if ($success) {
            echo '<p class="success">' . $success . '</p>';
        } elseif ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Delete Borrowed Book Form -->
        <form method="post" action="">
            <label for="borrowed_id">Borrowed ID:</label>
            <input type="text" name="borrowed_id" value="<?php echo $borrowedId; ?>" required maxlength="255">
            <button type="submit">Delete Borrowed Book</button>
        </form>
        <a href="admin_home.php">Back to Admin Home</a>
    </div>
</body>
</html>
