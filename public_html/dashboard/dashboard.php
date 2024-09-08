<?php
session_start();

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];
$name = $_SESSION['name'];

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
            /* General body styling */
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f7f6;
                margin: 0;
                padding: 0;
            }

            /* Top bar styling */
            .top-bar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background-color: #007bff; /* Top bar background color */
                color: white;
                padding: 15px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .top-bar .left {
                font-size: 18px;
                font-weight: bold;
            }

            .top-bar .center {
                font-size: 18px;
                font-weight: bold;
            }

            .top-bar .right {
                position: relative;
                top: 0;
            }

            /* Container for user info box */
            .user-info-box {
                background-color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-family: 'Arial', sans-serif;
                color: #333;
                font-size: 14px;
            }

            .user-info-box strong {
                font-weight: bold;
                color: #5cb85c;
            }

            .user-info-box p {
                margin: 5px 0;
                line-height: 1.5;
            }

            /* Smaller button above sign out button */
            .user-info-box .small-btn {
                background-color: #007bff;
                color: white;
                padding: 8px;
                border: none;
                border-radius: 5px;
                width: 80%;
                cursor: pointer;
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 10px;
                transition: background-color 0.3s ease;
            }

            .user-info-box .small-btn:hover {
                background-color: #0056b3;
            }

            /* Sign out button */
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
            }

            .user-info-box .sign-out-btn:hover {
                background-color: #4cae4c;
            }

            /* Padding for main content below the top bar */
            .main-content {
                padding-top: 80px; /* Adjust to leave space for the fixed top bar */
            }

        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="left">
                Website Title
            </div>
            <div class="center">
                Dashboard
            </div>
            <div class ="right">
                <div class="user-info-box">
                    <p>Hello, <strong><?php echo htmlspecialchars($name); ?></strong></p>
                    <p>Your user type is: <strong><?php echo htmlspecialchars($usertype); ?></strong></p>
                    <br>
                    <center><button class="small-btn" onclick="window.location.href='editprofile.php';">Edit Profile</button></center>
                    <form action="" method="post">
                        <button type="submit" class="sign-out-btn">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>