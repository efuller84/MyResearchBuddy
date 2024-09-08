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

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
            margin-bottom: 10px;
        }

        .submit-btn:hover {
            background-color: #4cae4c;
        }

        /* Smaller "Go Back" button styling */
        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3;
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

    </style>
</head>
<body>
    <script type="text/javascript">
        function confirmButton(event) {
            var confirmation = window.confirm('Confirm changes?');
            if (!confirmation) {
                event.preventDefault();
            }
        }
    </script>
    <div class="container">
        <h2>Edit Profile</h2>
        
        <form action="something.php" method="post" onsubmit="confirmButton(event)">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your new password" required>

            <!-- Confirm Button -->
            <button type="submit" class="submit-btn">Apply changes</button>

            <!-- Go Back Button -->
            <button type="button" class="back-btn" onclick="window.history.back()">Never mind</button>
        </form>
    </div>

</body>
</html>
