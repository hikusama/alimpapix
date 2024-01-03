<?php
// Include necessary files
require_once('includes/db_connect.php');
require_once('includes/functions.php');

// Start or resume session

$ImageData = $ImageType = "";

$defaultImagePath = "images/def.png";



$allowedExtensions = ["jpg", "jpeg", "png", "gif"]; // Add more if needed

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
    $imageExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $allowedExtensions)) {

        $ImageData = file_get_contents($defaultImagePath);
        $ImageType = mime_content_type($defaultImagePath);
    } else {


        $ImageData = file_get_contents($_FILES["image"]["tmp_name"]);
        $ImageType = $_FILES["image"]["type"];
    }
} else {
    $ImageData = file_get_contents($defaultImagePath);
    $ImageType = mime_content_type($defaultImagePath);
}
// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];



    // Validate and register user
    $result = register_user($username, $password, $email, $ImageData, $ImageType);

    if ($result === true) {
        // Registration successful, redirect to login page with success message
        header("Location: login.php?registration_success=true");
        exit();
    } else {
        $error_message = $result;
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

        nav button {
            border-radius: 5px;
            background-color: rgb(255, 111, 25);
            font-size: 1rem;
            height: 2.5rem;
            width: 5rem;
            transition: .2s;
            cursor: pointer;
            color: #fff;
            font-family: sans-serif;
            font-weight: 700;
        }

        nav button:hover {
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
            color: rgb(0, 176, 240) ;
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

 

        .register form {
            display: grid;
            place-items: center;
            row-gap: 1rem;
            margin: 1rem 0 2.2rem;
        }

        .register input {
            padding: 0 1rem;
            height: 2.5rem;
            width: 12rem;
            border-radius: 10px;
        }

        .register form button {
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

 
 

         .register a{
            color: lightskyblue;
        }
        .register p a:hover{
            color: blue;

        }
 




        .register {
            z-index: 2;
            transition: .3s;
            position: fixed;
            right: -24rem;
            border-radius: 18px;
            width: 23rem;
            display: grid;
            place-items: center;
            height: fit-content;
            padding: 1rem 0 2rem;
            background: rgba(37, 92, 147, .4);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(6.9px);
            -webkit-backdrop-filter: blur(6.9px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .picpackall {
            display: grid;
            place-items: center;
            width: 1;
        }

        .packim {
            height: 9rem;
            width: 9rem;
            border: solid 3px #fff;
            border-radius: 50%;
            place-items: end;

        }

        .packim li {
            height: 100%;
            width: 100%;
            display: grid;
            position: relative;
            place-items: center;
        }

        .packim li img {
            height: 8.69rem;
            width: 8.69rem;
            border-radius: 50%;
        }
        .packim label{
            position: absolute;
        }
        .packim .plus {
            background-color: rgb(51, 63, 80);
            height: 2rem;
            cursor: pointer;
            width: 2rem;
            border: solid 3.3px rgb(255, 255, 255);
            border-radius: 50%;
            display: grid;
            place-items: center;
            transition: .3s;
            top: 80%;
            right: 1rem;
        }

        .packim .plus:hover {
            transform: scale(.8) ;
        }

        .packim .plus:hover img {
            transform: rotate(180deg);
        }

        .packim .plus img {
            transition: transform .3s;
            height: 80%;
            width: 80%;

        }

        #image {
            visibility: hidden;
            height: .1px;
        }
        .register p{
            font-family: sans-serif;
            font-size: 1rem;
        }
        .register form p {
            text-align: center;
            width: 10rem;
            animation-name: bla;
            animation-iteration-count: infinite;
            animation-duration: .9s;
        }

        @keyframes bla {

            0%,
            100% {
                color: red;
            }

            50% {
                color: white;
            }
        }

        .register form button:hover {
            background-color: rgb(255, 111, 25);
        }
        .lg{
            right: 6%;
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


<label   onclick="regi()">

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

        <button onclick="regi()" type="button">
            Register
        </button>
    </nav>

    <div class="mdmsg">
        <h1>Online Library</h1>
        <h2>Learn nothing</h2>
        <button onclick="regi()">Get Started</button>
    </div>

 
 

        <div class="register" id="regs">
            <div class="rin">
                <div class="picpackall">
                    <div class="packim" id="packim">
                        <li>
                            <img id="profileImage" src="images/def.png" alt="prof">
                            <label for="image" class="plus">
                                <img src="images/plus.png" alt="add">
                            </label>
                        </li>
                    </div>
                </div>
                
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="image" id="image" accept="image/*" onchange="handleImageChange()">
                    <input placeholder="User name" type="text" name="username" required>
                    <input type="password" placeholder="Password" name="password" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <button type="submit" value="Submit">Register</button>
                    <?php if (isset($error_message)) : ?>
                        <p class="error"><?php echo $error_message; ?></p>
                    <?php endif; ?>
                </form>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>



    <script>
        function handleImageChange() {
            const profileImage = document.getElementById('profileImage');
            const input = document.getElementById('image');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function() {
                    profileImage.src = reader.result;
                };

                reader.readAsDataURL(file);
            } else {
                redpack.style.borderColor = 'white';
                profileImage.src = 'images/def.png';
            }
        }

        let rg = document.getElementById('regs');
        let ov = document.getElementById('ovv');

        function regi(){
            rg.classList.toggle('lg');
            ov.classList.toggle('ob');
        }
    </script>
</body>

</html>