<?php
session_start();

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page (or any page you want)
    header("Location: ../account/signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
        <style type = "text/css">
        /* Container for user info box */
        .user-info-box {
            position: fixed; /* Fixes the box to the corner */
            top: 20px;
            right: 20px;
            background-color: #fff; /* White background */
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
            font-family: 'Arial', sans-serif; /* Matches the rest of the page */
            color: #333; /* Dark text color */
            font-size: 14px; /* Small font size */
            z-index: 1000; /* Ensures it stays on top */
        }

        .user-info-box strong {
            font-weight: bold;
            color: #5cb85c; /* Matches the button style */
        }

        .user-info-box p {
            margin: 5px 0; /* Space between the lines */
            line-height: 1.5; /* Adjust line height */
        }

        .user-info-box a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff; /* Blue color for the logout link */
            font-size: 12px;
        }

        .user-info-box a:hover {
            text-decoration: underline;
            color: #0056b3; /* Darker blue on hover */
        }

        </style>
    </head>
    <body>
    <div class="user-info-box">
        <p>Hello, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
        <p>Your user type is: <strong><?php echo htmlspecialchars($usertype); ?></strong></p>
    </div>
    <form action="" method="post">
        <button type="submit">Sign Out</button>
    </form>
    </body>
</html>