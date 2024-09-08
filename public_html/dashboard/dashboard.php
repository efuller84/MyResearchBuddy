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
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-family: 'Arial', sans-serif;
                color: #333;
                font-size: 14px;
                z-index: 1000;
            }

            .user-info-box strong {
                font-weight: bold;
                color: #5cb85c;
            }

            .user-info-box p {
                margin: 5px 0;
                line-height: 1.5;
            }

            /* Button in the user info box (same style as submit button) */
            .user-info-box .sign-out-btn {
                background-color: #5cb85c;
                color: white;
                padding: 10px;
                border: none;
                border-radius: 5px;
                width: 100%;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
                transition: background-color 0.3s ease;
                margin-top: 10px; /* Space between text and button */
            }

            .user-info-box .sign-out-btn:hover {
                background-color: #4cae4c;
            }
        </style>
    </head>
    <body>
    <div class="user-info-box">
        <p>Hello, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
        <p>Your user type is: <strong><?php echo htmlspecialchars($usertype); ?></strong></p>
        <form action="" method="post">
            <button type="submit" class="sign-out-btn">Sign Out</button>
        </form>
    </div>
    </body>
</html>