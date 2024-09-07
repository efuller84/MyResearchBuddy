<?php
session_start();

// Check if the session is set
if (isset($_SESSION['username'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";
    echo "You are logged in as " . $_SESSION['userType'];
} else {
    // Redirect to login if not logged in
    header("Location: submit_signin.html");
    exit();
}
?>