 <?php
    // Include necessary files
    require_once('includes/db_connect.php');
    require_once('includes/functions.php');

    // Start or resume session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }

    // Get user information from the session
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Get user role from the session
    $user_role = $_SESSION['user_role'];

    // Function to search for books
    function searchBooks($conn, $search)
    {
        $search = mysqli_real_escape_string($conn, $search);
        $sql = "SELECT * FROM Books WHERE Title LIKE '%$search%' OR BookID = '$search'";
        return $conn->query($sql);
    }

    // Check if search query is present in the URL
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
        $search = $_GET['search'];
        $result = searchBooks($conn, $search);
    }
    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <title>Home</title>
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
             height: 100vh;
             width: 100vw;
         }

         .srch {
             margin-top: 40vh;
             display: grid;
             place-items: center;
         }

         .srch form input {
             height: 3.5rem;
             width: 16rem;
             padding: 0 2rem;
             border-radius: 15px;
             font-size: 1rem;
         }

         .srch form button {
             cursor: pointer;
             background: none;
             border: solid gray 2px;
             color: #fff;
             font-size: 1rem;
             font-family: sans-serif;
             height: 3.5rem;
             width: 5.5rem;
             border-radius: 7px;
             transition: .2s;
         }

         .srch form button:hover {
             background-color: green;
         }

         .bks {
             margin-top: 10rem;
             display: grid;
             place-items: center;
         }

         .inbks {
             width: 60rem;

         }

         .inbks h1 {
             color: gray;
             font-family: sans-serif;
         }

         .inbks h1 span {
             color: #fff;
         }

         .ap {
             position: absolute;
             top: 1rem;
             right: 1rem;
         }

         .ap button {
             transition: .3s;
             height: 2.5rem;
             font-size: 1rem;
             color: #fff;
             width: 8rem;
             background-color: maroon;
             cursor: pointer;
             border-radius: 7px;
         }

         .ap button:hover {
             transform: scale(.9);
         }
     </style>
 </head>

 <body>

     <?php if ($user_role === 'Admin') : ?>
         <a href="admin_home.php">Admin Home</a>
     <?php else : ?>
         <div class="srch">
             <img src="images/logo.png" alt="">
             <form method="get">
                 <input type="text" placeholder="Search for a book" name="search" required>
                 <button type="submit">Search</button>
             </form>
         </div>

         <?php if (isset($result)) : ?>
             <div class="bks">
                 <div class="inbks">
                     <h1>Results for : "<span><?php echo $search ?></span>".</h1>

                     <?php if ($result->num_rows > 0) : ?>
                         <ul>
                             <?php while ($row = $result->fetch_assoc()) : ?>
                                 <!-- Display book details here -->
                                 <li><?php echo $row['Title']; ?> - <?php echo $row['Author']; ?></li>
                             <?php endwhile; ?>
                         </ul>
                     <?php else : ?>
                         <p>No results found.</p>
                     <?php endif; ?>
                 </div>
             </div>
         <?php endif; ?>

         <a class="ap" href="logout.php">
             <button>Logout</button>
         </a>

     <?php endif; ?>

 </body>

 </html>