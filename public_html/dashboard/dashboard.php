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
    </head>
    <body>
    <p>Hello, <?php echo htmlspecialchars($username); ?>!</p>
    <p>Your user type is: <?php echo htmlspecialchars($usertype); ?>.</p>
    <form action="" method="post">
        <button type="submit">Sign Out</button>
    </form>
    </body>
</html>