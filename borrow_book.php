<?php
// Inside borrow_book.php
require_once('includes/db_connect.php');

session_start();

 

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    // If not logged in as admin, redirect to login page
    header("Location: login.php");
    exit();
}
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details from the database
    $sql = "SELECT * FROM Books WHERE BookID = '$book_id'";
    $result = $conn->query($sql);

    // Check if the book exists
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();

        // Generate BorrowID (you might want to adjust this logic)
        $borrow_id = uniqid();

        // Calculate ReturnDate (7 days from BorrowedDate)
        $borrow_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime($borrow_date . ' + 7 days'));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <style>
        body {
            background-color: #111927;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #1f2a38;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #ffd700;
            margin-top: 0;
        }

        p {
            margin: 10px 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        input[type="hidden"] {
            margin: 5px 0;
        }

        button {
            padding: 10px 20px;
            background-color: #ffd700;
            color: #111927;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #f5f5f5;
            color: #111927;
        }

        .error {
            color: #ff5555;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
              // Display detailed view of the book
              echo '<p>You\'ve borrowed a book.</p>';

              echo '<h2>' . $book['Title'] . '</h2>';
              echo '<p>Author: ' . $book['Author'] . '</p>';
              echo '<p>Published Year: ' . $book['PublishedYear'] . '</p>';
              echo '<p>BorrowID: ' . $borrow_id . '</p>';
              echo '<p>UserID: ' . $_SESSION['user_id'] . '</p>';
              echo '<p>BookID: ' . $book_id . '</p>';
              echo '<p>BorrowedDate: ' . date('d/m/Y', strtotime($borrow_date)) . '</p>';
              echo '<p>ReturnDate: ' . date('d/m/Y', strtotime($return_date)) . ' Time: 5:00 pm</p>';
      
              // Add a form for the user to confirm borrowing
              echo '<form method="post" action="borrow_process.php">';
              echo '<input type="hidden" name="borrow_id" value="' . $borrow_id . '">';
              echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
              echo '<input type="hidden" name="book_id" value="' . $book_id . '">';
              echo '<input type="hidden" name="borrow_date" value="' . $borrow_date . '">';
              echo '<input type="hidden" name="return_date" value="' . $return_date . ' 17:00:00">'; 
      
              echo '<button type="submit">Confirm Borrow</button>';
              echo '</form>';
      
              echo '<form method="post" action="home.php">';
              echo '<button type="submit">Return</button>';
              echo '</form>';
      
        ?>
    </div>
</body>
</html>
<?php
 
    } else {
        // Handle case where the book doesn't exist
        echo 'Book not found.';
    }
} 
else {
    // Redirect to home.php if no book ID is provided
    header("Location: home.php");
    exit();
}
?>
