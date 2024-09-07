<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Check if both fields are not empty
        if (!empty($username) && !empty($password)) {
            echo "Username and Password provided.";
            // You can add further validation or authentication logic here
        } else {
            echo "Both Username and Password must be filled out.";
        }
    } else {
        echo "Username and Password fields are required.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
<br><br>
<a href="index.html">Go back</a>