<?php
// Connect to the database
$servername = "localhost";
$username = "root"; // Adjust this to match your database credentials
$password = "";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_POST['professor_id'];  // Assuming professor is logged in and we have the ID
    $project_name = $_POST['project_name'];
    $project_location = $_POST['project_location'];
    $project_description = $_POST['project_description'];
    $tags = $_POST['tags'];  // Comma-separated tag IDs
    $capacity_total = $_POST['capacity_total'];
    $is_archived = isset($_POST['is_archived']) ? 1 : 0; // Checkbox to archive the project

    // SQL to insert project
    $sql = "INSERT INTO Projects (professor_id, project_name, project_location, project_description, tags, capacity_total, is_archived)
            VALUES ('$professor_id', '$project_name', '$project_location', '$project_description', '$tags', '$capacity_total', '$is_archived')";

    if ($conn->query($sql) === TRUE) {
        echo "New project created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Project</title>
</head>
<body>
    <h2>Create a New Project</h2>
    <form method="POST" action="">
        <label for="professor_id">Professor ID:</label><br>
        <input type="number" id="professor_id" name="professor_id" required><br>

        <label for="project_name">Project Name:</label><br>
        <input type="text" id="project_name" name="project_name" required><br>

        <label for="project_location">Project Location:</label><br>
        <input type="text" id="project_location" name="project_location"><br>

        <label for="project_description">Project Description:</label><br>
        <textarea id="project_description" name="project_description" required></textarea><br>

        <label for="tags">Tags (comma-separated tag IDs):</label><br>
        <input type="text" id="tags" name="tags"><br>

        <label for="capacity_total">Total Capacity:</label><br>
        <input type="number" id="capacity_total" name="capacity_total" required><br>

        <label for="is_archived">Archive this project:</label>
        <input type="checkbox" id="is_archived" name="is_archived"><br>

        <input type="submit" value="Create Project">
    </form>
</body>
</html>
