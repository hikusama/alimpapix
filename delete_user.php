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
$userId = $username = $success = $error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userId = $_POST['user_id'];

    // Validate form data
    if (empty($userId)) {
        $error = "User ID is required.";
    } else {
        // Delete associated borrowed books first
        $sqlDeleteBooks = "DELETE FROM BorrowedBooks WHERE UserID = ?";
        $stmtDeleteBooks = $conn->prepare($sqlDeleteBooks);
        $stmtDeleteBooks->bind_param("i", $userId);

        if ($stmtDeleteBooks->execute()) {
            // Now delete the user
            $sql = "DELETE FROM Users WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);

            if ($stmt->execute()) {
                // Check the number of affected rows
                $affectedRows = $stmt->affected_rows;

                if ($affectedRows > 0) {
                    $success = "User deleted successfully!";
                } else {
                    $error = "No user found with ID: $userId";
                }
            } else {
                // Handle error
                $error = "Error deleting user: " . $stmt->error;
            }

            $stmt->close();
        } else {
            // Handle error
            $error = "Error deleting associated borrowed books: " . $stmtDeleteBooks->error;
        }

        $stmtDeleteBooks->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete User</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: berlin;
            src: url(berlin.TTF);
        }

        body {
            font-family: berlin;
            color: #fff;
            background-color: #111927;
        }

        .container form {
            display: grid;
            place-items: center;
            height: 7rem;
        }

        .container {
            row-gap: .5rem;
            display: flex;
            justify-content: center;
            flex-direction: column;
            place-items: center;
            text-align: center;
            border-radius: 25px;
            transform: translate(-50%, -50%);
            position: absolute;
            top: 50%;
            left: 50%;
            height: 18rem;
            width: 15rem;
            width: 18rem;
            background: linear-gradient(gray, lightblue, lightskyblue);
        }

        .make {
            row-gap: 1rem;
            display: grid;
            place-items: center;
        }

        .make input {
            text-align: center;
            padding: 0 2rem;
            height: 2rem;
            width: 12rem;
            font-size: 1rem;
            border-radius: 10px;
        }

        .make button {
            cursor: pointer;
            border-radius: 8px;
            background-color: maroon;
            height: 2rem;
            color: #fff;
            width: 9rem;
            font-size: 1rem;
            transition: .3s;
        }

        button:hover {
background-color: red;
        }
        .aaa button{
            cursor: pointer;
            border-radius: 4px;
            height: 2rem;
            width: 13rem;
            background-color: gray;
            color: #fff;
            font-size: .9rem;
            
        }
        .aaa button:hover{
            background-color: #111927;
        }
    </style>
</head>

<body>
    <div class="container">

        <?php
        // Display success or error message
        if ($success) {
            echo '<p class="success">' . $success . '</p>';
        } elseif ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>

        <!-- Delete User Form -->
        <form method="post" action="">
            <div class="make">

                <input type="text" placeholder="Enter the id" name="user_id" value="<?php echo $userId; ?>" required>
                <button type="submit">Delete User</button>
            </div>
        </form>


        <a class="aaa" href="admin_home.php">
            <button>
                Back to Admin Home
            </button>

        </a>
    </div>
</body>

</html>