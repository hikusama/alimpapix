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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Home</title>
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
            display: grid;
            grid-template-columns: 20rem auto;
            font-family: berlin;
            color: #fff;
            background-color: #111927;
            transition: .4s;
        }

        .sidepane {
            border-right: solid 1px #fff;
            height: 100vh;
            width: 20rem;
            background-color: rgb(51, 63, 80);
        }

        .sidepane .insidep {
            margin-top: 4rem;
            display: grid;
            place-items: center;
        }

        .sidepane .insidep li {
            list-style: none;
        }

        .sidepane .insidep button {
            padding: 1rem 0;
        }

        .sidepane .insidep button {
            width: 100%;
            background-color: gray;
            color: #fff;
            font-variant: small-caps;
            font-family: sans-serif;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 10px;
            transition: .3s;
            cursor: pointer;
        }

        .sidepane .insidep button:hover {
            background-color: maroon;

        }

        .sidepane .insidep ul {
            row-gap: 1rem;
            display: grid;
            margin-top: 2rem;
        }

        .sidepane .insidep li a {
            font-family: sans-serif;
            color: #fff;
            font-size: 1.4rem;
            text-decoration: none;
        }

        .sidepane img {
            height: 6rem;
        }

        .ctu {
            height: 100vh;
            position: absolute;

        }

        .contents {
            overflow-y: scroll;
            height: 100vh;
            position: relative;
            width: 100%;
            right: 0;
            background-color: #111927;

        }

        .overley {
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, .6);
            height: 100%;
            width: 100%;
            border-radius: 0px;
            border-radius: 14px;

        }

        .mnlk {
            z-index: 2;
            top: 50%;
            left: 1.2rem;
            position: absolute;
            height: 4rem;
            width: .8rem;
            border-radius: 15px;
            cursor: pointer;
            transition: .3s;
            background-color: gray;
        }

        .mnlk:hover {
            background-color: #fff;
        }

        .mnlk:hover .overley {
            z-index: 3 !important;
        }


        .nbd {
            grid-template-columns: 0 100%;
        }

        /* userrrrrrrrrrrrrrrrrrrrrr */
        #a{
            color: #00acee;
        }
        .insidep ul li a:hover{
            color: #00acee;
        }
    </style>
</head>

<body>

    <div class="sidepane">
        <div class="overley" id="ovv"></div>

        <div class="insidep">
            <div class="img">
                <img src="images/logo.png" alt="">
            </div>
            <ul>
                <li><a id="a" href="#">Dashboard</a></li>
                <li><a id="b" href="view_books.php">Books</a></li>
                <li><a id="c" href="borrowed_table.php">Borrowed Books</a></li>
                <li><a id="d" href="admin_user.php">Users</a></li>

                <a href="logout.php">
                    <button>
                        Logout
                    </button>
                </a>
            </ul>



            </label>
        </div>
    </div>

    <div class="ctt">
        <div class="ctu">

            <label class="mnlk" onclick="hide()" id="ds">
            </label>
        </div>

        <div class="contents">


            <div class="prof">
                <div class="dn">

 
                </div>
            </div>
        </div>





    </div>

    <script>
        var element = document.getElementById('ds');
        var ov = document.getElementById('ovv');


        element.addEventListener('mouseover', function() {
            ov.style.display = 'block';
        });


        element.addEventListener('mouseout', function() {

            ov.style.display = 'none';

        });

        let bd = document.body;

        function hide() {
            bd.classList.toggle('nbd');
        }
    </script>
</body>

</html>