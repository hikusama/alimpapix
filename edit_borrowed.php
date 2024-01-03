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
$borrowId = $userId = $returnDate = $success = $error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $borrowId = $_POST['borrow_id'];
    $userId = $_POST['user_id'];
    $returnDate = $_POST['return_date'];

    // Validate form data
    if (empty($borrowId) || empty($userId) || empty($returnDate)) {
        $error = "All fields are required.";
    } else {
        // Edit the return date for the borrowed book in the database
        $conn = connectToDatabase();
        
        // Fetch the existing return date for the specified BorrowID and UserID
        $fetchSql = "SELECT ReturnDate FROM BorrowedBooks WHERE BorrowID = ? AND UserID = ?";
        $fetchStmt = $conn->prepare($fetchSql);
        $fetchStmt->bind_param("si", $borrowId, $userId);
        $fetchStmt->execute();
        $fetchStmt->bind_result($existingReturnDate);
        $fetchStmt->fetch();
        $fetchStmt->close();

        if ($existingReturnDate !== null) {
            // If the return date exists, update it
            $updateSql = "UPDATE BorrowedBooks SET ReturnDate = ? WHERE BorrowID = ? AND UserID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssi", $returnDate, $borrowId, $userId);

            if ($updateStmt->execute()) {
                $success = "Return date updated successfully!";
            } else {
                $error = "Error updating return date: " . $updateStmt->error;
            }

            $updateStmt->close();
        } else {
            $error = "No matching record found with BorrowID: $borrowId and UserID: $userId";
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
    <title>Edit Borrowed Book</title>
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
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
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
        <h2>Edit Borrowed Book</h2>

        <?php
        // Display success or error message
        if ($success) {
            echo '<p class="success">' . $success . '</p>';
        } elseif ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Edit Borrowed Book Form -->
        <form method="post" action="">
            <label for="borrow_id">Borrowed ID:</label>
            <input type="text" name="borrow_id" value="<?php echo $borrowId; ?>" required maxlength="255">

            <label for="user_id">User ID:</label>
            <input type="text" name="user_id" value="<?php echo $userId; ?>" required maxlength="255">

            <label for="return_date">Return Date:</label>
            <input type="text" name="return_date" value="<?php echo $existingReturnDate ?? ''; ?>" required>

            <button type="submit">Update Return Date</button>
        </form>

        <a href="admin_home.php">Back to Admin Home</a>
    </div>
</body>
</html>

