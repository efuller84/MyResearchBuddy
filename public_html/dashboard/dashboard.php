<?php
session_start();

$username = $_SESSION['username'];
$usertype = $_SESSION['userType'];
// Check if the session is set
if (isset($_SESSION['username']) || isset($_SESSION['userType'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";
    echo "You are logged in as " . $_SESSION['userType'];
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
    <form action="logout.php" method="post">
        <button type="submit">Sign Out</button>
    </form>
    </body>
</html>