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
    // Check if username, password, and userType are set
    if (isset($_POST['username'], $_POST['password'], $_POST['userType'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $userType = trim($_POST['userType']);

        // Ensure that none of the fields are empty
        if (!empty($username) && !empty($password) && !empty($userType)) {
            // Check if the username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Username is already taken
                $error = "Username already exists. Please choose a different username.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO users (username, password, userType) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hashed_password, $userType);

                if ($stmt->execute()) {
                    echo "Sign-up successful! You can now log in.";
                    // Optionally, redirect to the login page
                    // header("Location: login.php");
                    // exit;
                } else {
                    $error = "Error signing up. Please try again.";
                }
            }

            $stmt->close();
        } else {
            $error = "All fields (Username, Password, and User Type) are required.";
        }
    } else {
        $error = "Username, Password, and User Type fields are required.";
    }

    if (isset($error)) {
        echo $error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
<br><br>
<a href="signup.html">Go back</a>
