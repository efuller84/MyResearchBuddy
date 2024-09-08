<?php
session_start();
// Database connection
$servername = "127.0.0.1";
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
        $usertype = trim($_POST['usertype']);

        // Check if fields are not empty
        if (!empty($username) && !empty($password)) {
            if($usertype == "Student") {
                //$usertype = "Student";
                $stmt = $conn->prepare("SELECT s_password FROM students WHERE s_username = ?");
            } else {
                //$usertype = "Professor";
                $stmt = $conn->prepare("SELECT p_password FROM professors WHERE p_username = ?");
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
                        $_SESSION['usertype'] = $usertype;
                        header("Location: ../dashboard/dashboard.php");
                        exit();
            
                    } else {
                        $error = "Invalid password.";
                        exit();
            
                    }
                } else {
                    $error = "Invalid username.";
                    //header("Location: ../dashboard/dashboard.php");
                    exit();
                }
            }
            $stmt->close();       
        } else if(empty($username)){
            $error = "Invalid username!";
            //header("Location: ../dashboard/dashboard.php");

        } else if(empty($password)){
            $error = "Invalid password!";
            //header("Location: ../dashboard/dashboard.php");

        } else if(empty($usertype)){
            $error = "Invalid user type!";
            //header("Location: ../dashboard/dashboard.php");
        }
    }

if (!empty($error)) {
    echo "<script type='text/javascript'>
        alert('" . addslashes($error) . "');
        window.location.href = 'signin.php?error=" . urlencode($error) . "';
    </script>";
}

$conn->close();
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
    <form method="post" action="">
    <label for="usertype">Sign in as:</label>
    <select id="usertype" name="usertype">
        <option value="Student" selected>Student</option>
        <option value="Professor">Professor</option>
    </select>
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
    <p>No account? Sign up <a href="signup.php">here</a>.</p>
</body>
</html>
