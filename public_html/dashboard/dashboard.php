<?php
session_start();

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];
exit();
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