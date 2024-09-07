<?php
session_start();
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
                        $_SESSION['username'] = $username;
                        $_SESSION['userType'] = $usertype;
                        echo "Login successful!";
                        header("Location: dashboard/dashboard.html");
            
                    } else {
                        $error = "Invalid password.";
                        header("Location: submit_signin.html");
            
                    }
                } else {
                    $error = "Invalid username.";
                    header("Location: submit_signin.html");
        
                }
            }
            $stmt->close();       
        } else if(empty($username)){
            echo "Invalid username!";
            header("Location: submit_signin.html");

        } else if(empty($password)){
            echo "Invalid password!";
            header("Location: submit_signin.html");

        } else if(empty($usertype)){
            echo "Invalid user type!";
            header("Location: submit_signin.html");

        }
    } else {
        header("Location: submit_signin.html");
}

if (!empty($error)) {
    // Embed JavaScript that triggers an alert in the HTML
    echo "<script type='text/javascript'>alert('" . addslashes($error) . "');</script>";
}

exit();
$conn->close();
header("Location: index.html");
?>