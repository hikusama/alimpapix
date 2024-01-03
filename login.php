<?php
// Include necessary files
require_once('includes/db_connect.php');
require_once('includes/functions.php');


// Start or resume session
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Validate user credentials
    $user = validate_login($username, $password);
    $conf = 0;
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['username'] = $user['Username'];

        // Debugging output
        error_log("Redirecting user with role: " . $_SESSION['user_role']);

        // Redirect based on user role
        if ($_SESSION['user_role'] === 'Admin') {
            $linkss = "admin_home.php";
            include('welcomepannel.php');
            exit();
        } else {
            $linkss = "home.php";
            include('welcomepannel.php');
            exit();
        }
    } else {
        $error_message = "Invalid username or password.";
    }
} elseif (isset($_SESSION['user_role'])) {
    // After a successful login, redirect the user based on their role
    if ($_SESSION['user_role'] === 'Admin') {
        header("Location: admin_home.php");
        exit();
    } else {
        header("Location: home.php");
        exit();
    }
}
?>

<!-- HTML form for login -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

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
            height: 100vh;
            width: 100vw;
            background-image: url('images/lognbg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        nav {
            line-height: 1.5rem;
            display: flex;
            justify-content: space-between;
            padding: 2rem 8%;
            align-items: center;
        }

        nav ul {
            display: flex;
            flex-direction: row;
            column-gap: 3rem;
        }

        nav ul li {
            list-style: none;
            position: relative;
            display: grid;
            place-items: center;
        }

        nav ul li::before {

            border-radius: 4px;
            content: '';
            z-index: -1;
            height: 0;
            width: 0;
            transition: .2s;
            position: absolute;
            background-color: rgb(255, 111, 25);
        }

        nav ul li:hover::before {
            height: 100%;
            width: 100%;
        }

        nav ul li a {
            padding: 3px 7px;
            text-decoration: none;
            color: #fff;
            font-size: 1.1rem;
        }

        nav label {
            border-radius: 5px;
            background-color: rgb(0, 176, 240);
            font-size: 1rem;
            height: 2.5rem;
            width: 5rem;
            transition: .2s;
            cursor: pointer;
            color: #fff;
            font-family: sans-serif;
            font-weight: 700;
            display: grid;
            place-items: center;
        }

        nav label:hover {
            transform: scale(.9);
        }

        .mdmsg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: grid;
            place-items: center;
        }

        .mdmsg h1 {
            font-size: 4rem;
        }

        .mdmsg h2 {
            font-size: 2.5rem;
            -webkit-text-stroke: #fff;
            color: rgb(0, 176, 240);
        }

        .mdmsg button {
            cursor: pointer;
            margin-top: 2rem;
            height: 3rem;
            width: 13rem;
            border: solid #fff 2px;
            background-color: black;
            color: #fff;
            font-size: 1.2rem;
            border-radius: 18px;
            position: relative;
            transition: .2s;
        }

        .mdmsg button:hover {
            background-color: rgb(255, 111, 25);

        }

        .logn {
            transition: .3s;
            right: -21rem;
            position: fixed;
            height: fit-content;
            border-radius: 18px;
            padding: 4rem 0 2rem;
            width: 19rem;
            display: grid;
            place-items: center;
            z-index: 2;
            background: rgba(37, 92, 147, .4);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(6.9px);
            -webkit-backdrop-filter: blur(6.9px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

 

        .logn form {
            display: grid;
            place-items: center;
            row-gap: 1rem;
            margin: 0 0 2.2rem;
        }

        .logn form input {
            padding: 0 1rem;
            height: 2.5rem;
            width: 12rem;
            border-radius: 10px;
        }

        .logn form button{
            border-radius: 8px;
            height: 2.5rem;
            width: 6rem;
            background: none;
            color: #fff;
            border: solid white 2px;
            transition: .3s;
            cursor: pointer;
            font-size: 1rem;

        }

        .logn form button:hover {
            background-color: rgb(255, 111, 25);
        }

        .logn form p {
            animation-name: bl;
            animation-iteration-count: infinite;
            animation-duration: .6s;
        }

        @keyframes bl {

            0%,
            100% {
                color: red;
            }

            50% {
                color: white;
            }
        }

        .logn p  {
            font-family: sans-serif;
            font-size: 1rem;
        }

        .logn p a {
            color: lightskyblue;
        }
        .register p a:hover{
            color: blue;

        }
        .logn p a:hover {
            color: blue;
        }

        .lg{
            right: 10%;
        }

        .oberley{
            display: none;
            background-color: rgba(0, 0, 0, 0.6);
            height: 100%;
            width:  100%;
            position: fixed;
            z-index: 1;
        }
        .ob{
            display: block;
        }
 


    </style>
</head>

<body>

<label   onclick="logon()">

    <div class="oberley" id="ovv"></div>
</label>
    <nav>
        <ol>

            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">How to use</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </ol>
        
            <label   onclick="logon()">
                Log-in

            </label>
</nav>

    <div class="mdmsg">
        <h1>Online Library</h1>
        <h2>Learn nothing</h2>
        <button onclick="logon()">Get Started</button>
    </div>



        <div class="logn" id="logn">
            <div class="loginin">
 

                <form method="post">
                    <?php if (isset($error_message)) : ?>
                        <p class="error"><?php echo $error_message;
                                        endif; ?></p>
                        <input placeholder="User name" type="text" name="username" required>
                        <input type="password" placeholder="Password" name="password" required>
                        <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
 



    <script>
 


        let login = document.getElementById('logn');
        let ov = document.getElementById('ovv');

        function logon(){
            login.classList.toggle('lg');
            ov.classList.toggle('ob');
        }
    </script>
</body>

</html>