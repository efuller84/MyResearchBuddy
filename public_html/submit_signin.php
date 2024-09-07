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
        if (!empty($username) && !empty($password) && isset($_POST['userType'])) {
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username); // "s" means the parameter is a string
            $stmt->execute();
            $stmt->store_result();

            // Check if the user exists
            if ($stmt->num_rows > 0) {
                // Bind result to a variable
                $stmt->bind_result($hashed_password);
                $stmt->fetch();

                // Verify the password (assuming passwords are hashed)
                if (password_verify($password, $hashed_password)) {
                    echo "Login successful!";
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Invalid username.";
            }
            $stmt->close();       
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