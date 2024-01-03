<?php
// Include necessary files
require_once('includes/db_connect.php');

// Get the database connection
$conn = connectToDatabase();

// Fetch users from the database
$sql = "SELECT * FROM users";

$result = $conn->query($sql);

// Check if there are users
if ($result->num_rows > 0) {

    // Output data of each user
    while ($row = $result->fetch_assoc()) {
        $usid = $row['UserID'];
        $imageData = $row['image_data'];
        $imageType = $row['image_type'];


        $dCount = strlen((string)$usid);

        $rl = $row['UserRole'];

        $rlC = ($rl == 'Admin') ? "gold" : "silver";


        switch ($dCount) {
            case 1:
                $usid =  '000' . $usid;
                break;
            case 2:
                $usid = '00' . $usid;
                break;

            case 3:
                $usid = '0' . $usid;

                break;
            default:

                break;
        }
?>



        <ol>
            <div class="rl" style="background-color: <?php echo $rlC ?>;">
                <a>
                    <?php echo $rl ?>
                </a>
            </div>


            <li>
                <div class="ctt">

                    <h3><?php echo $row['Username'] ?> </h3>
                    <h5>user name</h5>
                </div>
            </li>



            <li id="persli">
                <div class="pic">
                    <img id="pictur" src="data:<?php echo $imageType; ?>;base64,<?php echo base64_encode($imageData); ?>" alt="Image  ">
                </div>

                <div class="id">

                    <h6>
                        <?php

                        echo $usid;
                        ?>
                    </h6>
                    <h5>user id</h5>
                </div>
            </li>







        </ol>
    <?php        // echo '<tr>';
        // echo '<td>' . $row['UserID'] . '</td>';
        // echo '<td>' . $row['Username'] . '</td>';
        // echo '<td>' . $row['Email'] . '</td>';
        // echo '<td>' . $row['UserRole'] . '</td>';
        // echo '</tr>';
    }

    echo '</table>';
    // Display "Edit" and "Delete" links for admin
    if ($_SESSION['user_role'] === 'Admin') {
    ?>

        <a class="a" href="delete_user.php">
            <button>
                Delete User
            </button>
        </a>


<?php
    }
} else {
    echo 'No registered users.';
}

// Close the connection
$conn->close();
?>