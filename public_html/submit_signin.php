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
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['userType'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $usertype = trim($_POST['userType']);

        // Check if fields are not empty
        if (!empty($username) && !empty($password) && !empty($usertype)) {
            if($usertype == "userTypeS") {
                $stmt = $conn->prepare("SELECT password FROM Students WHERE username = ?");
            } else {
                $stmt = $conn->prepare("SELECT password FROM Professors WHERE username = ?");
            }
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
                        header("Location: submit_signin.html");
                        exit();
                    }
                } else {
                    $error = "Invalid username.";
                    header("Location: submit_signin.html");
                    exit();
                }
            }
            $stmt->close();       
        } else if(empty($username)){
            echo "Invalid username!";
            header("Location: submit_signin.html");
            exit();
        } else if(empty($password)){
            echo "Invalid password!";
            header("Location: submit_signin.html");
            exit();
        } else if(empty($usertype)){
            echo "Invalid user type!";
            header("Location: submit_signin.html");
            exit();
        }
    } else {
        header("Location: submit_signin.html");
        exit();
}

$conn->close();
header("Location: index.html");
?>
<br><br>
<a href="index.html">Go back</a>