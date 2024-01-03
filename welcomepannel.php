<?php
require_once('includes/db_connect.php');
require_once('includes/functions.php');





?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

        .welco {
            font-size: 2.5rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: grid;
            place-items: center;
            row-gap: 1rem;
        }

        .welco h2 span {
            text-transform: uppercase;
            color: rgb(0, 176, 240);
        }

        .welco button {

            animation-name: wlwl;
            background-color: rgb(0, 176, 240);
            height: 3.5rem;
            width: 10rem;
            border-radius: 20px;
            transition: .3s;
            animation-duration: 3s;
            animation-iteration-count: infinite;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.5rem;
            color: #fff;
            font-family: berlin;
        }

 
        @keyframes wlwl {

            0%,
            100% {
                transform: scale(.9);
            }

            50% {
                transform: scale(1.1);

            }
        }

        .welco button:hover {
            animation-play-state: paused;
        }
    </style>
</head>

<body>
    <div class="welco" id="wel">

        <h2>Welcome, <span> <?php echo $username; ?></span></h2>
        <a href="<?php echo $linkss ?>">
            <button>
                Continue
            </button>
        </a>
    </div>
</body>

</html>