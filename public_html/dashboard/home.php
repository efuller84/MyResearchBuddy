<?php
session_start();

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "my_database";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch session variables for user info display
$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];  // 'Student' or 'Professor'
$name = $_SESSION['name'];
$useremail = $_SESSION['email'];
$password = $_SESSION['password'];
$tags = $_SESSION['tags'];
// Fetch professor_id dynamically from the database based on the username
if ($usertype == 'Professor') {
    $professor_query = "SELECT professor_id FROM Professors WHERE p_username = '$username'";
    $professor_result = mysqli_query($conn, $professor_query);

    if ($professor_result && mysqli_num_rows($professor_result) > 0) {
        $professor_data = mysqli_fetch_assoc($professor_result);
        $professor_id = $professor_data['professor_id'];
    } else {
        echo "No professor found with the username.";
        exit();
    }
}

// Handle sign-out
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page (or any page you want)
    header("Location: ../account/signin.php");
    exit();
}

// Fetch projects only if the user is a professor
if ($usertype == 'Professor') {
    // Query to get active projects for the professor
    $active_projects_query = "
        SELECT project_id, project_name, project_description, tags 
        FROM Projects 
        WHERE professor_id = $professor_id AND is_archived = 0";
    $active_projects_result = mysqli_query($conn, $active_projects_query);

    // Query to get archived projects for the professor
    $archived_projects_query = "
        SELECT project_id, project_name, project_description, tags 
        FROM Projects 
        WHERE professor_id = $professor_id AND is_archived = 1";
    $archived_projects_result = mysqli_query($conn, $archived_projects_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <style type="text/css">
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
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .top-bar .left {
            display: flex;
            font-size: 18px;
            font-weight: bold;
        }

        .top-bar .left img {
            height: 150px; /* Adjust size of the image */
            margin-right: 10px; /* Space between image and Dashboard text */
        }

        .top-bar .center {
            font-size: 30px; /* Larger font size */
            font-weight: bold;
            letter-spacing: 0px; /* Optional for letter spacing */
            margin-left: 10px; /* Extra margin to keep consistent spacing */
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
            margin-right: 40px;
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
    padding-top: 150px; /* Increased from 80px to give more space */
    padding-left: 20px; /* Optional: Adds a bit of padding to the left */
}

   /* Create Project (Plus button without tooltip) */
.plus-btn {
    font-size: 40px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
    position: relative; /* This can remain */
}

.plus-btn:hover {
    background-color: #218838;
}

        /* Project listing */
        .project-panel {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .project-panel h4 {
            margin: 0;
            font-size: 20px;
        }

        .project-panel p {
            margin: 5px 0;
        }

        .project-list {
            margin-bottom: 40px;
        }

    </style>
</head>
<body>
    <div class="top-bar">
        <div class="left">
            <img src="../../MyResearchBuddy.png" height="150" width="150" alt="My Research Buddy Logo">
        </div>
        <div class="center">
            My Research Buddy <i><div style="font-size: 10px; text-align: right">v1.0h - Hackathon Edition</div></i>
        </div>
        <div class="right">
            <div class="user-info-box">
                <p><center><strong><?php echo htmlspecialchars($name); ?></strong><center></p>
                <p><center><strong><?php echo htmlspecialchars($usertype); ?></strong><center></p>
                <br>
                <center><button class="small-btn" onclick="window.location.href='settings.php';">Edit Account Settings</button></center>
                <form action="" method="post">
                    <button type="submit" class="sign-out-btn">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php if ($usertype == 'Professor'): ?>
            <h2>Manage Projects</h2>
            
           <!-- Create Project Button (without tooltip) -->
<button class="plus-btn" onclick="window.location.href='newproject.php';">
    + 
</button>


            <!-- Active Projects Section -->
            <h3>Active Projects</h3>
            <div class="project-list">
                <?php while ($project = mysqli_fetch_assoc($active_projects_result)): ?>
                    <div class="project-panel">
                        <h4><?php echo htmlspecialchars($project['project_name']); ?></h4>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($project['project_description']); ?></p>
                        <p><strong>Tags:</strong> <?php echo htmlspecialchars($project['tags']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Archived Projects Section -->
            <h3>Archived Projects</h3>
            <div class="project-list">
                <?php while ($project = mysqli_fetch_assoc($archived_projects_result)): ?>
                    <div class="project-panel">
                        <h4><?php echo htmlspecialchars($project['project_name']); ?></h4>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($project['project_description']); ?></p>
                        <p><strong>Tags:</strong> <?php echo htmlspecialchars($project['tags']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
