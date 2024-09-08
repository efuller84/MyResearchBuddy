<?php
session_start();

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];
$name = $_SESSION['name'];
$useremail = $_SESSION['email']; 
$password = $_SESSION['password'];

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 300px;
            padding: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content label {
            display: block;
            margin-bottom: 5px;
        }

        /* CSS Grid for 5 columns */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }
    </style>
</head>
<body>
    <h2>Create a New Project</h2>
    <form method="POST" action="">

        <label for="project_name">Project Name:</label><br>
        <input type="text" id="project_name" name="project_name" required><br>

        <label for="project_location">Project Location:</label><br>
        <input type="text" id="project_location" name="project_location"><br>

        <label for="project_description">Project Description:</label><br>
        <textarea id="project_description" name="project_description" required></textarea><br>

        <label for="tags">Tags (comma-separated tag IDs):</label><br>
        <input type="text" id="tags" name="tags"><br>


        <div class="dropdown">
        <button>Select Items</button>
        <div class="dropdown-content">
            <div class="checkbox-grid">
                <label><input type="checkbox" name="items" value="item1"> Item 1</label>
                <label><input type="checkbox" name="items" value="item2"> Item 2</label>
                <label><input type="checkbox" name="items" value="item3"> Item 3</label>
                <label><input type="checkbox" name="items" value="item4"> Item 4</label>
                <label><input type="checkbox" name="items" value="item5"> Item 5</label>
                <label><input type="checkbox" name="items" value="item6"> Item 6</label>
                <label><input type="checkbox" name="items" value="item7"> Item 7</label>
                <label><input type="checkbox" name="items" value="item8"> Item 8</label>
                <label><input type="checkbox" name="items" value="item9"> Item 9</label>
                <label><input type="checkbox" name="items" value="item10"> Item 10</label>
                <label><input type="checkbox" name="items" value="item11"> Item 11</label>
                <label><input type="checkbox" name="items" value="item12"> Item 12</label>
                <label><input type="checkbox" name="items" value="item13"> Item 13</label>
                <label><input type="checkbox" name="items" value="item14"> Item 14</label>
                <label><input type="checkbox" name="items" value="item15"> Item 15</label>
                <!-- Add more items as needed -->
            </div>
        </div>
    </div>


        <label>Project Status:</label><br>
        <input type="radio" id="current" name="project_status" value="current">
        <label for="current">Current</label><br>
        <input type="radio" id="archive" name="project_status" value="archive">
        <label for="archive">Archive</label><br>

        <label for="capacity_total">Total Capacity:</label><br>
        <input type="number" id="capacity_total" name="capacity_total" required><br>

        <input type="submit" value="Create Project">
    </form>
</body>
</html>
