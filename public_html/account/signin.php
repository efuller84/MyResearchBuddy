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
                $stmt = $conn->prepare("SELECT s_password, s_name, s_email FROM students WHERE s_username = ?");
            } else {
                $stmt = $conn->prepare("SELECT p_password, p_name, p_email FROM professors WHERE p_username = ?");
            }
                $stmt->bind_param("s", $username); // "s" means the parameter is a string
                $stmt->execute();
                $stmt->store_result();
    
                // Check if the user exists
                if ($stmt->num_rows > 0) {
                    // Bind result to a variable
                    $stmt->bind_result($userpass, $userid, $useremail);
                    $stmt->fetch();
    
                    // Verify the password
                    if ($userpass == $password) {
                        $_SESSION['username'] = $username;
                        $_SESSION['usertype'] = $usertype;
                        $_SESSION['name'] = $userid;
                        $_SESSION['email'] = $useremail;
                        header("Location: ../dashboard/home.php");
                        exit();
            
                    } else {
                        $error = "Invalid password!";
            
                    }
                } else {
                    $error = "Invalid username!";
                }
            }
            $stmt->close();       
        } else if(empty($username)){
            $error = "Invalid username!";

        } else if(empty($password)){
            $error = "Invalid password!";
            

        } else if(empty($usertype)){
            $error = "Invalid user type!";
        }
    }

if (!empty($error)) {
    echo "<script type='text/javascript'>
        alert('" . addslashes($error) . "');
        window.location.href = 'signin.php';
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

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            padding-top: 50px;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
        }

        .radio-group {
            margin-bottom: 20px;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
        }

        .submit-btn {
            background-color: #5cb85c;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #4cae4c;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .dropdown {
            margin-top: 20px;
        }

        .dropdown button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .dropdown button:hover {
            background-color: #0056b3;
        }

        .dropdown-content {
            display: none;
            background-color: #f9f9f9;
            padding: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown-content label {
            margin-bottom: 10px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Adjust as needed */
            gap: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            font-size: 14px;
            color: #555;
            appearance: none; /* Remove default dropdown arrow */
            -webkit-appearance: none; /* For Safari */
            -moz-appearance: none; /* For Firefox */
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><polygon fill="%23555555" points="0,0 5,5 10,0"/></svg>'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
        }

select:focus {
    outline: none;
    border-color: #5cb85c;
}

select option {
    padding: 10px;
}
    </style>
</head>
<body>
    <div class="container">
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
            <input type="submit" value="Sign In" class="submit-btn">
        </form>
        <br>
        <div class="form-footer">
            <p>No account? Sign up <a href="signup.php">here</a>.</p>
    </div>
    </div>
</body>
</html>
