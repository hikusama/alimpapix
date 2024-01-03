<?php
$unameds;
// Function to validate user login
function validate_login($username, $password) {
    global $conn;

    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check user credentials
    $sql = "SELECT * FROM Users WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, return user data
        $user = $result->fetch_assoc();

        // Set user role in the session
        $_SESSION['user_role'] = $user['UserRole'];

        // Debugging output
        error_log("User Role: " . $_SESSION['user_role']);

        return $user;
    }
}








// Function to register a new user
function register_user($username, $password, $email, $imagedata, $imagetype) {
    global $conn;

    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $email = mysqli_real_escape_string($conn, $email);

    // Check if username is already taken
    $check_username = "SELECT * FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($check_username);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username already taken. Please choose a different one.";
    }

    // Use prepared statement for inserting data
    $insert_user = "INSERT INTO users (Username, Password, Email,image_data, image_type, UserRole) VALUES (?, ?, ?,  ?, ?, 'Regular')";
    $stmt = $conn->prepare($insert_user);
    $stmt->bind_param("sssss", $username, $password, $email, $imagedata, $imagetype);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true; // Registration successful
    } else {
        return "Error: " . $stmt->error;
    }
}











// Function to fetch book details by BookID
function fetch_book_details($bookID) {
    global $conn;

    // Sanitize input
    $bookID = mysqli_real_escape_string($conn, $bookID);

    // Query to fetch book details
    $sql = "SELECT * FROM books WHERE BookID = '$bookID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Book found, return book data
        $book = $result->fetch_assoc();
        return $book;
    } else {
        // Book not found
        return null;
    }
}
?>
