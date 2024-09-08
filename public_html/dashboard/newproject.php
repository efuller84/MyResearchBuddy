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
// Fetch professor_id dynamically from the database based on the username

$professor_query = "SELECT professor_id FROM Professors WHERE p_username = '$username'";
$professor_result = mysqli_query($conn, $professor_query);

if ($professor_result && mysqli_num_rows($professor_result) > 0) {
    $professor_data = mysqli_fetch_assoc($professor_result);
    $professor_id = $professor_data['professor_id'];
} else {
    echo "No professor found with the username.";
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_name = $_POST['project_name'];
    $project_location = $_POST['project_location'];
    $project_description = $_POST['project_description'];

    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $tags_str = implode(",", $tags); // Convert selected tag IDs to a comma-separated string

    $capacity_current = $_POST['capacity_current'];
    $capacity_total = $_POST['capacity_total'];
    $is_archived = $_POST['project_status'] === 'archive' ? 1 : 0;; // Radio button to determine status
    $project_application_link = $_POST['project_application_link'];

    // SQL to insert project
    $sql = "INSERT INTO Projects (professor_id, project_name, project_location, project_description, tags, capacity_current, capacity_total, is_archived, project_application_link)
            VALUES ('$professor_id', '$project_name', '$project_location', '$project_description', '$tags_str', '$capacity_current', '$capacity_total', '$is_archived', '$project_application_link')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../dashboard/home.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Project</title>

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

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"]
        input[type="number"] 
        input[type="link"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
        }

        .radio-group {
            margin-bottom: 20px;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
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
            margin-bottom: 20px;
        }

        .submit-btn:hover {
            background-color: #4cae4c;
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

        .dropdown {
            margin-top: 20px;
        }

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

        .dropdown button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .dropdown button:hover {
            background-color: #0056b3;
        }

        .dropdown-content {
            display: none;
            background-color: #f9f9f9;
            padding: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown-content label {
            margin-bottom: 10px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Adjust as needed */
            gap: 10px;
        }
        
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Project</h2>

        <form action="" method="post" onsubmit="return validateForm()">
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required>

            <label for="project_location">Project Location:</label>
            <input type="text" id="project_location" name="project_location">

            <label for="project_description">Project Description:</label>
            <input type="text" name="project_description" required>

            <label for="capacity_current">Selected Applicants:</label>
            <input type="number" id="capacity_current" name="capacity_current" required><br>
            <br>
            <label for="capacity_total">Capacity:</label>
            <input type="number" id="capacity_total" name="capacity_total" required><br>
            <br>
            <label for="project_application_link">Project Application Link (optional):</label>
            <input type="link" name="project_application_link">
            <br>
            <div class="project_status">
                <br>
                <label for="current"><input type="radio" id="current" name="project_status" value="current"> Current</label>
                <label for="archive"><input type="radio" id="archive" name="project_status" value="archive"> Archived</label>
            </div>

            <!-- Tags dropdown for students (initially hidden) -->
            <div class="dropdown" id="tagsDropdown">
                <button type="button" onclick="toggleTags()">Select Tags</button>
                <div class="dropdown-content" id="tagsContent">
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="tags[]" value="1"> AI</label>
                        <label><input type="checkbox" name="tags[]" value="2"> Machine Learning</label>
                        <label><input type="checkbox" name="tags[]" value="3"> Data Science</label>
                        <label><input type="checkbox" name="tags[]" value="4"> Robotics</label>
                        <label><input type="checkbox" name="tags[]" value="5"> Quantum Computing</label>
                    </div>
                </div>
            </div> <br>

            <br>
            <input type="submit" value="Create Project" class="submit-btn">
            <!-- Go Back Button -->
            <button type="button" class="back-btn" onclick="window.history.back()">Never mind</button>
        </form>

    </div>

    <script>

    // Toggle visibility of the tags content
    function toggleTags() {
        var content = document.getElementById('tagsContent');
        if (content.style.display === "none" || content.style.display === "") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }
    </script>
</body>
</html>