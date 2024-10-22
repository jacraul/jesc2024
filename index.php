<?php
// Include your database connection file
include 'dbconn.php';
header('Content-Type: text/html; charset=UTF-8');


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['options']) && count($_POST['options']) == 3) {
        // Insert votes into the database
        $selectedOptions = $_POST['options'];
        foreach ($selectedOptions as $option) {
            // Increment votes for the selected option
            $sql = "UPDATE votes SET votes = votes + 1 WHERE running_order = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $option);
            $stmt->execute();
        }

        // Pop-up for successful voting
        echo "<script>alert('Voting successful!');</script>";
    } else {
        // Pop-up for unsuccessful voting (less or more than 3 options selected)
        echo "<script>alert('Please select exactly 3 options.');</script>";
    }
}

// Fetch options from the database to display
$sql = "SELECT * FROM votes";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote for Your Favorite Songs</title>
    <style>
        @font-face {
            font-family: JESC2024;
            src: url(GasoekOne-Regular.ttf);
        }

        @font-face {
            font-family: GM;
            src: url(gm.otf);
        }

        *{font-family:GM}

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('back.jpeg'); /* Replace with your background image */
            background-size: cover;
            background-attachment: fixed;
        }
        .logo {
            padding-top:50px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 200px;
        }
        .container {
            width: 90%;
            max-width:600px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
        }
        .option {
            display: flex;
            align-items: center;
            margin: 10px 0;
            background-color:#4F225F;
            color:white
        }
        .option img {
            width: 70px;
            height: 70px;
            margin: 5px;
        }

        .option .heart {
            height: 20px;
            width: auto;
            margin-right: 5px;
            margin:0px;
        }

        .option input {
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background: linear-gradient(180deg, rgba(218,29,93,1) 0%, rgba(238,83,41,1) 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: linear-gradient(180deg, rgba(218,29,93,1) 0%, rgba(238,83,41,1) 100%);
        }
    </style>
    <script>
        function validateForm() {
            let checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checkedBoxes.length !== 3) {
                alert("Please select exactly 3 options.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<img class="logo" src="logo.png">

    <div class="container">
        <h1>Vote for Your Favorite Songs</h1>
        <h3>Choose 3 songs!</h3>
        <i>Disclaimer: this is a simulation made by Euro Alfa</i>
        <form method="POST" onsubmit="return validateForm();">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="option">
                    <table style="width:100%">
                        <tr>
                        <!-- 1st column for image -->
                        <td style="width:5%">
                            <img src="contestants/<?php echo $row['image']; ?>.webp" alt="<?=$row['image']?>" width="100">
                        </td>

                        <!-- 2nd column for text and heart image -->
                        <td style="width:85%">
                            <p style="font-size:18px; margin: 5px 0; font-family:JESC2024">
                            <img class="heart" src="Hearts/<?php echo $row['heart']; ?>.webp" alt="<?=$row['heart']?>" width="20" style="margin-right: 5px;"> 
                            <?=strtoupper($row['country'])?>
                            </p>
                            <p style="margin: 5px 0;"><?=strtoupper($row['artist'])?></p>
                            <p style="margin: 5px 0;"><?=$row['song']?></p>
                        </td>

                        <!-- 3rd column for checkbox -->
                        <td style="width:10%">
                            <label>
                            <input type="checkbox" name="options[]" value="<?php echo $row['running_order']; ?>">
                            </label>
                        </td>
                        </tr>
                    </table>
                 </div>
            <?php endwhile; ?>
            <p style="text-align:center"><button type="submit">Submit Vote</button></p>
        </form>
        <BR>
        <BR>
        <h3>Website by Raul Jac</h3>
    </div>
</body>
</html>
