<?php
// Include the database connection
include('dbconn.php');  // Assuming 'dbconn.php' contains the connection code

// Set the correct password
$correct_password = ""; // Change this to your desired password

// Initialize session to track if user is authenticated
session_start();

// If the user submits the form and the password is correct
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $correct_password) {
        // Set session to authenticated
        $_SESSION['authenticated'] = true;
    } else {
        // If password is incorrect, unset session and show alert
        $_SESSION['authenticated'] = false;
        $error_message = "Incorrect password!";
    }
}

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // If not authenticated, show password form
    echo '<form method="POST" action="">
        <label for="password">Enter Password:</label>
        <input type="password" name="password" id="password">
        <button type="submit">Submit</button>
    </form>';
    
    // Display alert if password is incorrect
    if (isset($error_message)) {
        echo "<script>alert('$error_message');</script>";
    }
    
    exit(); // Stop the script execution here if not authenticated
} else {
    // If authenticated, display the results from the database
    ?>
    <h1>Protected Results</h1>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Place</th>
                <th>Country</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query to get place, country, and votes from the database
            $sql = "SELECT country, votes FROM votes ORDER BY votes DESC"; // Replace "your_table_name" with the actual table name
            $result = mysqli_query($conn, $sql);

            // Check if there are any results
            if (mysqli_num_rows($result) > 0) {
                $k=1;
                // Output each row of data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . $k . "</td>
                            <td>" . $row['country'] . "</td>
                            <td>" . $row['votes'] . "</td>
                          </tr>";
                          $k+=1;
                }
            } else {
                echo "<tr><td colspan='3'>No results found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <p><a href="?logout=true">Logout</a></p>

    <?php
    // Logout if user clicks the logout link
    if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
        session_destroy(); // End the session
        header("Location: ".$_SERVER['PHP_SELF']); // Reload page
        exit();
    }
}
?>
