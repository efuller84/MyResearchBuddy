<?php
session_start();
// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$usertype = $_SESSION['usertype'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
$tags = $_SESSION['tags'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data

    $action_type = $_POST['action_type'];

    if ($action_type == 'edit') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']); 

        if ($usertype == "Student") {
            $tags_string = trim(implode(',', $_POST['tags']));
            $stmt = $conn->prepare("UPDATE students SET s_name = ?, s_email = ?, s_password = ?, tags = ? WHERE s_username = ?");
            $stmt->bind_param('sssss', $name, $email, $password, $tags_string, $username);
            $_SESSION['tags'] = $_POST['tags']; 
        } else {
            $stmt = $conn->prepare("UPDATE professors SET p_name = ?, p_email = ?, p_password = ? WHERE p_username = ?"); 
            $stmt->bind_param('ssss', $name, $email, $password, $username);
        }
    
        // Bind the form data to the query
    
        // Execute the query
        $stmt->execute();
    
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        
        // Redirect to a success page
        header("Location: home.php");
        exit();
    
    
        // Close the connection
        $conn = null;
    } elseif ($action_type == 'delete') {
        if ($usertype == "Student") {
            $stmt = $conn->prepare("DELETE FROM students WHERE s_username = ?");
        } else {
            $stmt = $conn->prepare("DELETE FROM professors WHERE p_username = ?");
        }

        // Prepare and execute the deletion query
        $stmt->bind_param('s', $username);
        $stmt->execute();

        // Destroy the session and redirect to a goodbye page or home page
        session_destroy();
        header("Location: ../account/signin.php"); // Replace with appropriate page
        exit();
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account Settings</title>
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

        label {
            font-size: 14px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
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
            margin-bottom: 10px;
        }

        .submit-btn:hover {
            background-color: #4cae4c;
        }

        /* Smaller "Go Back" button styling */
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

        
        .delete-btn {
            background-color: #d9534f; /* Red color */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            margin-bottom: 5px; /* Space between Delete and Go Back buttons */
        }

        .delete-btn:hover {
            background-color: #c9302c; /* Darker red on hover */
        }

        .dropdown {
            margin-top: 20px;
        }

        .dropdown button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
        }

        .dropdown button:hover {
            background-color: #0056b3;
        }

        .dropdown-content {
            background-color: #f9f9f9;
            padding: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 1px;
            margin-bottom: 10px;
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
    <script type="text/javascript">
        function confirmButton(event) {
            var confirmation = window.confirm('Confirm changes?');
            if (!confirmation) {
                event.preventDefault();
            }
        }

        function confirmDelete() {
            if (confirm("Press OK to delete your account. This action cannot be undone.")) {
                document.getElementById('action_type').value = 'delete'; // Change the action to delete
                document.getElementById('edit-form').submit(); // Submit the form
            }
        }

    </script>
    <div class="container">
        <h2>Edit Account Settings</h2>
        
        <center> Username: 
        <?php
        echo htmlspecialchars($_SESSION['username']);
        ?>
        </center>
        <br>
        <form id = "edit-form" action="" method="post" onsubmit="confirmButton(event)">
            <label for="name">Name (Max length of 20 characters):</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($_SESSION['password']); ?>" required>

            <?php
                // Show content based on the user type
                if ($_SESSION['usertype'] == 'Student') {
                    // Content for students
                    echo '<div class="dropdown" id="tagsDropdown"">';
                        echo '<div class="dropdown-content" id="tagsContent">';
                            echo '<div class="checkbox-grid">';

                                echo '<label><input type="checkbox" name="tags[]" value="1"';
                                    if (in_array("1", $_SESSION["tags"])) {
                                        echo ' checked';
                                    }
                                echo '> AI</label>';

                                echo '<label><input type="checkbox" name="tags[]" value="2"';
                                    if (in_array('2', $_SESSION['tags'])) {
                                        echo ' checked';
                                    }
                                echo '> Machine Learning</label>';
                                
                                echo '<label><input type="checkbox" name="tags[]" value="3"';
                                    if (in_array('3', $_SESSION['tags'])) {
                                        echo ' checked';
                                    }
                                echo '> Data Science</label>';
                                
                                echo '<label><input type="checkbox" name="tags[]" value="4"';
                                if (in_array('4', $_SESSION['tags'])) {
                                    echo ' checked';
                                }
                                echo '> Robotics</label>';
                                
                                echo '<label><input type="checkbox" name="tags[]" value="5"';
                                if (in_array('5', $_SESSION['tags'])) {
                                    echo ' checked';
                                }
                                echo '> Quantum Computing</label>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            ?>



            <input type="hidden" id="action_type" name="action_type" value="edit">

            <!-- Confirm Button -->
            <button type="submit" class="submit-btn">Apply changes</button>

            <!-- Go Back Button -->
            <button type="button" class="back-btn" onclick="window.history.back()">Never mind</button>

            <button type="button" class="delete-btn" onclick="confirmDelete()">Delete my account (Permanent!!)</button>
        </form>
    </div>
</body>
</html>
