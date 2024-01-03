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

        .prof {
            position: relative;
            display: grid;
            place-items: center;
            margin-top: 5rem;
        }

        .dn {
            width: fit-content;
            display: grid;
            place-items: center;
            row-gap: 1.5rem;

        }

        .prof ol {
            position: relative;
            display: grid;
            grid-template-columns: 1fr .8fr;
            overflow: hidden;
            place-items: center;
            width: 30rem;
            background: linear-gradient(#2c586e, #2c586e3e, #2c586e);
            border-radius: 20px;
            border: solid white 1px;
            gap: 1rem;
            padding: 1.5rem 1rem;
        }


        .prof ol::before {
            content: '';
            background: #9f9f9f;
            position: absolute;
            height: 70%;
            left: -.4rem;
            z-index: 1;
            width: 2rem;
            border-radius: 40%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .prof ol::after {
            content: '';
            background: #9f9f9f;
            position: absolute;
            height: 70%;
            border-radius: 40%;
            right: -2.4rem;
            z-index: 1;
            width: 2rem;
            top: 50%;
            transform: translate(-50%, -50%);
        }


        .prof ol b {
            font-variant: small-caps;
            position: absolute;
            right: 1rem;
            font-size: 1.1rem;
            top: 0;
            height: 1.5rem;
            width: 2.1rem;
            text-transform: lowercase;
            color: rgb(255, 255, 255);
            border-radius: 0 0 8px 8px;
        }







        .prof li {
            height: 100%;
            display: grid;
            z-index: 2;
            place-items: center;
            list-style: none;
            width: 100%;
        }




        .prof li h6 {
            color: #9b9b9b;
            font-weight: 900;
            font-family: monospace;
            font-size: 1.4rem;
        }








        .prof h3 {
            color: #ffffff;
            font-family: sans-serif;
            font-size: 1.8rem;
            font-variant: small-caps;
            font-weight: 600;
            text-transform: lowercase;
        }





        .prof h4 {
            position: relative;
            font-family: sans-serif;
            color: #9f9f9f;
            position: relative;
            text-transform: lowercase;
            font-weight: 700;
            font-size: 1.4rem;
            font-variant: small-caps;
        }

        .prof h5 {
            color: #00acee;
            font-size: 1rem;
            font-variant: small-caps;
            font-family: sans-serif;
        }


        #pictur {
            border: solid 1px #fff;
            border-radius: 20px;
            height: 7rem;
            width: 7rem;
            margin: 4px;
            z-index: 2;
        }

        .id {
            text-align: center;
        }

        .pic {
            position: relative;
            display: grid;
            overflow: hidden;
            place-items: center;
            width: 7.9rem;
        }

        .pic::after {
            content: '';
            background: #00acee;
            position: absolute;
            height: 50%;
            right: -.5rem;
            z-index: 1;
            width: 1rem;
        }

        .pic::before {
            content: '';
            background: #00acee;
            position: absolute;
            height: 50%;
            left: -.5rem;
            z-index: 1;
            width: 1rem;
        }

        .ctt {
            text-align: center;
        }

        .rl {
            height: 2rem;
            width: 6rem;
            position: absolute;
            left: 1rem;
            top: 0;
            border-radius: 0 0 15px;
            display: grid;
            place-items: center;
        }

        .rl a {
            font-weight: 900;
            font-size: 1.05rem;
            font-family: monospace;
            text-transform: uppercase;
            font-variant: small-caps;
            color: #111927;
        }

        #aa {
            text-transform: uppercase;
            font-variant: small-caps;
            font-size: 2.5rem;
        }
        .a button{
            cursor: pointer;
            border-radius: 8px;
            background-color: maroon;
            height: 2rem;
            color: #fff;
            width: 9rem;
            font-size: 1rem;
            transition: .3s;
        }
        .a button:hover{
            transform: scale(.9);
        }
        .aaa button{
            height: 3.5rem;
            width: 7rem;
            
        }
 


        .dash img {
            height: 4rem;
        }
        #d{
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
                <li><a id="a" href="admin_home.php">Dashboard</a></li>
                <li><a id="b" href="view_books.php">Books</a></li>
                <li><a id="c" href="borrowed_table.php">Borrowed Books</a></li>
                <li><a id="d" href="#">Users</a></li>

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

                    <h2 id="aa">User</h2>

                    <?php include('user_table.php'); ?>
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