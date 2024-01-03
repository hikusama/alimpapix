<?php
// Include necessary files
require_once('includes/db_connect.php');
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_role'])) {
    // Redirect to the login page or perform other authentication actions
    header("Location: login.php");
    exit();
}

// Fetch books from the database based on search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
if (!empty($search)) {
    $sql = "SELECT * FROM Books WHERE Title LIKE '%$search%' OR Author LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM Books";
}
$result = $conn->query($sql);

// Include Bootstrap CSS and JS files
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Books</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap\css\bootstrap.min.css">
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
            color: #fff;
            background-color: #111927;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <?php
        // Display search form
        ?>
        <form method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search for a book" name="search" value="<?= htmlspecialchars($search) ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>
        <?php

        // Check if there are books
        if ($result->num_rows > 0) {
        ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Published Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $row['BookID'] ?></td>
                            <td><?= $row['Title'] ?></td>
                            <td><?= $row['Author'] ?></td>
                            <td><?= $row['Genre'] ?></td>
                            <td><?= $row['PublishedYear'] ?></td>
                            <td>
                                <a href="borrow_book.php?book_id=<?= $row['BookID'] ?>" class="btn btn-primary">Borrow</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php

            // Display "Add Book" link for admin
            if ($_SESSION['user_role'] === 'Admin') {
                echo '<a href="add_book.php" class="btn btn-success">Add Book</a>';
            }
        } else {
            echo '<p class="alert alert-info">No books available. <a href="add_book.php">Add a Book</a></p>';
        }
        ?>
    </div>

    <!-- Bootstrap JS (optional, if you need Bootstrap features) -->
    <script src="path/to/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>