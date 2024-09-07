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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 300px;
            padding: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content label {
            display: block;
            margin-bottom: 5px;
        }

        /* CSS Grid for 5 columns */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(20, 1fr);
            gap: 10px;
        }
    </style>

</head>
<body>
    <h2>Sign Up</h2>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>
        <label for="password">Re-enter Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>

        <div class="dropdown">
            <button>Select Items</button>
            <div class="dropdown-content">
                <div class="checkbox-grid">
                    <label><input type="checkbox" name="items" value="item1"> Item 1</label>
                    <label><input type="checkbox" name="items" value="item2"> Item 2</label>
                    <label><input type="checkbox" name="items" value="item3"> Item 3</label>
                    <label><input type="checkbox" name="items" value="item4"> Item 4</label>
                    <label><input type="checkbox" name="items" value="item5"> Item 5</label>
                    <label><input type="checkbox" name="items" value="item6"> Item 6</label>
                    <label><input type="checkbox" name="items" value="item7"> Item 7</label>
                    <label><input type="checkbox" name="items" value="item8"> Item 8</label>
                    <label><input type="checkbox" name="items" value="item9"> Item 9</label>
                    <label><input type="checkbox" name="items" value="item10"> Item 10</label>
                    <label><input type="checkbox" name="items" value="item11"> Item 11</label>
                    <label><input type="checkbox" name="items" value="item12"> Item 12</label>
                    <label><input type="checkbox" name="items" value="item13"> Item 13</label>
                    <label><input type="checkbox" name="items" value="item14"> Item 14</label>
                    <label><input type="checkbox" name="items" value="item15"> Item 15</label>
                    <label><input type="checkbox" name="items" value="item1"> Item 1</label>
                    <label><input type="checkbox" name="items" value="item2"> Item 2</label>
                    <label><input type="checkbox" name="items" value="item3"> Item 3</label>
                    <label><input type="checkbox" name="items" value="item4"> Item 4</label>
                    <label><input type="checkbox" name="items" value="item5"> Item 5</label>
                    <label><input type="checkbox" name="items" value="item6"> Item 6</label>
                    <label><input type="checkbox" name="items" value="item7"> Item 7</label>
                    <label><input type="checkbox" name="items" value="item8"> Item 8</label>
                    <label><input type="checkbox" name="items" value="item9"> Item 9</label>
                    <label><input type="checkbox" name="items" value="item10"> Item 10</label>
                    <label><input type="checkbox" name="items" value="item11"> Item 11</label>
                    <label><input type="checkbox" name="items" value="item12"> Item 12</label>
                    <label><input type="checkbox" name="items" value="item13"> Item 13</label>
                    <label><input type="checkbox" name="items" value="item14"> Item 14</label>
                    <label><input type="checkbox" name="items" value="item15"> Item 15</label>
                    <label><input type="checkbox" name="items" value="item1"> Item 1</label>
                    <label><input type="checkbox" name="items" value="item2"> Item 2</label>
                    <label><input type="checkbox" name="items" value="item3"> Item 3</label>
                    <label><input type="checkbox" name="items" value="item4"> Item 4</label>
                    <label><input type="checkbox" name="items" value="item5"> Item 5</label>
                    <label><input type="checkbox" name="items" value="item6"> Item 6</label>
                    <label><input type="checkbox" name="items" value="item7"> Item 7</label>
                    <label><input type="checkbox" name="items" value="item8"> Item 8</label>
                    <label><input type="checkbox" name="items" value="item9"> Item 9</label>
                    <label><input type="checkbox" name="items" value="item10"> Item 10</label>
                    <label><input type="checkbox" name="items" value="item11"> Item 11</label>
                    <label><input type="checkbox" name="items" value="item12"> Item 12</label>
                    <label><input type="checkbox" name="items" value="item13"> Item 13</label>
                    <label><input type="checkbox" name="items" value="item14"> Item 14</label>
                    <label><input type="checkbox" name="items" value="item15"> Item 15</label>
                    <!-- Add more items as needed -->
                </div>
            </div>
        </div>

        <br><br>
        <input type="submit" value="Sign Up">
    </form>
    <br>
    <p>If you have an account, please <a href="signin.html">sign in</a>.</p>
</body>
</html>