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

// Fetch the tags session variable, ensure it's an array, and debug output
$tags = isset($_SESSION['tags']) ? $_SESSION['tags'] : [];
if (empty($tags)) {
    echo "<p>Error: No tags found in the session.</p>";
} else {
    echo "<p>Student tags: " . implode(',', $tags) . "</p>"; // Debug output for tags
}

// Handle sign-out
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign_out'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page (or any page you want)
    header("Location: ../account/signin.php");
    exit();
}

// Initialize recommended projects array (for students)
$recommended_projects = [];

// Function to get tag names from tag IDs
function getTagNames($tag_ids, $conn) {
    $tag_ids_array = explode(',', $tag_ids);  // Split tag IDs into an array
    $tag_ids_string = implode(',', array_map('intval', $tag_ids_array));  // Ensure all IDs are integers
    $query = "SELECT tag_name FROM tags WHERE id IN ($tag_ids_string)";
    $result = mysqli_query($conn, $query);
    $tag_names = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tag_names[] = $row['tag_name'];
    }
    return implode(', ', $tag_names);  // Return a comma-separated list of tag names
}

// Fetch projects based on user type
if ($usertype == 'Student') {
// Handle search submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_text'])) {
    $_SESSION['search_text'] = $_POST['search_text'];
    header("Location: listprojects.php");
    exit();
}
    // Ensure tags are valid and not empty
    if (!empty($tags) && is_array($tags) && count($tags) > 0) {
        $tag_string = implode(',', $tags);  // Create a comma-separated string of tag IDs

        // Check if the first tag is not empty before proceeding
        if (!empty($tags[0])) {
            // Query for projects that match at least one tag and exclude archived projects
            $query = "
                SELECT project_id, project_name, project_description, tags, project_application_link
                FROM Projects
                WHERE is_archived = 0 AND FIND_IN_SET($tags[0], tags) > 0";

            // Add OR conditions for other tags
            for ($i = 1; $i < count($tags); $i++) {
                $query .= " OR FIND_IN_SET($tags[$i], tags) > 0";
            }

            // Limit the results to 5 projects
            $query .= " LIMIT 5";

            echo "<p>Executing query: $query</p>";  // Debug output for query

            // Execute the query
            $result = mysqli_query($conn, $query);

            // Fetch the recommended projects
            while ($project = mysqli_fetch_assoc($result)) {
                $recommended_projects[] = $project;
            }
        } else {
            echo "<p>Error: No valid tags found to query.</p>";
        }
    } else {
        echo "<p>Error: No tags available in session.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <style type="text/css">
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #007bff;
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
            height: 150px;
            margin-right: 10px;
        }

        .top-bar .center {
            font-size: 30px;
            font-weight: bold;
            letter-spacing: 0px;
            margin-left: 10px;
        }

        .top-bar .right {
            position: relative;
            top: 0;
        }

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

        .main-content {
            padding-top: 150px;
            padding-left: 20px;
        }
.search-box {
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-box input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .search-box input[type="submit"]:hover {
            background-color: #0056b3;
        }

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

        .action-btn {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .email-btn {
            background-color: #007bff;
            color: white;
        }

        .email-btn:hover {
            background-color: #0056b3;
        }

        .apply-btn {
            background-color: #28a745;
            color: white;
        }

        .apply-btn:hover {
            background-color: #218838;
        }

    </style>
</head>
<body>
    <div class="top-bar">
        <div class="left">
            <img src="../MyResearchBuddy.png" height="150" width="150" alt="My Research Buddy Logo">
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
                    <button type="submit" class="sign-out-btn" name="sign_out">Sign Out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php if ($usertype == 'Student'): ?>

<!-- Search Box -->
            <div class="search-box">
                <form method="post" action="">
                    <input type="text" name="search_text" placeholder="Search for projects..." required>
                    <input type="submit" value="Search">
                </form>
            </div>
            <h2>Recommended Projects</h2>

            <!-- Recommended Projects Section -->
            <div class="project-list">
                <?php if (empty($recommended_projects)): ?>
                    <p>No recommended projects found based on your tags.</p>
                <?php else: ?>
                    <?php foreach ($recommended_projects as $project): ?>
                        <div class="project-panel">
                            <h4><?php echo htmlspecialchars($project['project_name']); ?></h4>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($project['project_description']); ?></p>
                            <p><strong>Tags:</strong> <?php echo htmlspecialchars(getTagNames($project['tags'], $conn)); ?></p>

                            <!-- Email and Apply buttons -->
                            <button class="action-btn email-btn" onclick="window.location.href='mailto:professor_email@example.com'">Email</button>
                            <button class="action-btn apply-btn" onclick="window.location.href='<?php echo htmlspecialchars($project['project_application_link']); ?>'">Apply</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
