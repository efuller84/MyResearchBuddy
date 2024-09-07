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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>
<body>
    <h2>Sign In</h2>
    <form action="" method="post">
        <label for="userTypeS">Student: </label>
        <input type="radio" id="userTypeS" name="userType">
        <label for="userTypeP">Professor: </label>
        <input type="radio" id="userTypeP" name="userType">
        <br><br>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>
        <input type="submit" value="Sign In">
    </form>
    <br>
    <p>No account? Sign up <a href="signup.html">here</a>.</p>
</body>
</html>
