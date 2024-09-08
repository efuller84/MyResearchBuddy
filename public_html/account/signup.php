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

$error = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all necessary fields are set
    if (isset($_POST['username'], $_POST['password'], $_POST['userType'], $_POST['email'], $_POST['name'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $rePassword = trim($_POST['rePassword']);
        $email = trim($_POST['email']);
        $userType = trim($_POST['userType']);
        $name = trim($_POST['name']);

        // Ensure fields are not empty and passwords match
        if (!empty($username) && !empty($password) && !empty($userType) && !empty($email) && $password === $rePassword) {

            if ($userType == "student") {
                // Handle student signup
                $stmt = $conn->prepare("SELECT student_id FROM Students WHERE s_username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error = "Username already exists. Please choose a different username.";
                } else {
                    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
                    $tags_str = implode(",", $tags); // Convert selected tag IDs to a comma-separated string

                    $stmt = $conn->prepare("INSERT INTO Students (s_username, s_name, s_password, s_email, field_of_research, tags) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $username, $name, $password, $email, $tags_str, $tags_str);

                    if ($stmt->execute()) {
                        // Set session variables and redirect to dashboard
                        $_SESSION['username'] = $username;
                        $_SESSION['usertype'] = "Student";
                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;
                        $_SESSION['tags'] = $tags;
                        header("Location: ../dashboard/home.php");
                        exit();
                    } else {
                        $error = "Error signing up as a student. Please try again.";
                    }
                }
                $stmt->close();
            } elseif ($userType == "professor") {
                // Handle professor signup
                $stmt = $conn->prepare("SELECT professor_id FROM Professors WHERE p_username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $error = "Username already exists. Please choose a different username.";
                } else {
                    $stmt = $conn->prepare("INSERT INTO Professors (p_username, p_name, p_password, p_email) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $name, $password, $email);

                    if ($stmt->execute()) {
                        // Set session variables and redirect to dashboard
                        $_SESSION['username'] = $username;
                        $_SESSION['usertype'] = "Professor";
                        $_SESSION['name'] = $name;
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;
                        header("Location: ../dashboard/home.php");
                        exit();
                    } else {
                        $error = "Error signing up as a professor. Please try again.";
                    }
                }
                $stmt->close();
            } else {
                $error = "Invalid user type selected.";
            }

        } else {
            $error = "All fields (Username, Password, Email, Name, and User Type) are required, and passwords must match.";
        }
    } else {
        $error = "All required fields must be filled out.";
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

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
        input[type="password"] {
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
        <h2>Sign Up</h2>

        <!-- Display error messages inside the form -->
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="post" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="name">Full Name (Max length of 20 characters): </label>
            <input type="text" name="name" id="name"  required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="rePassword">Re-enter Password:</label>
            <input type="password" name="rePassword" id="rePassword" required>

           <!-- Buttons to select whether the user is a Student or Professor -->
            <div class="radio-group">
                <label for="student"><input type="radio" id="student" name="userType" value="student" onclick="toggleTagsDropdown()"> Student</label>
                <label for="professor"><input type="radio" id="professor" name="userType" value="professor" onclick="toggleTagsDropdown()"> Professor</label>
            </div>

            <!-- Tags dropdown for students (initially hidden) -->
            <div class="dropdown" id="tagsDropdown" style="display:none;">
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
            </div>

            <br>
            <input type="submit" value="Sign Up" class="submit-btn">
        </form>

        <div class="form-footer">
            <p>If you have an account, please <a href="signin.php">sign in</a>.</p>
        </div>
    </div>

    <script>
    // Show/Hide the tags dropdown based on the selected user type
    function toggleTagsDropdown() {
        var studentSelected = document.getElementById('student').checked;
        var tagsDropdown = document.getElementById('tagsDropdown');
        
        if (studentSelected) {
            tagsDropdown.style.display = 'block'; // Show the tags dropdown for students
        } else {
            tagsDropdown.style.display = 'none'; // Hide the tags dropdown for professors
        }
    }

    // Toggle visibility of the tags content
    function toggleTags() {
        var content = document.getElementById('tagsContent');
        if (content.style.display === "none" || content.style.display === "") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }

    // Validate the form to check if passwords match
    function validateForm() {
        var password = document.getElementById('password').value;
        var rePassword = document.getElementById('rePassword').value;

        if (password !== rePassword) {
            alert("Passwords do not match!");
            return false; // Prevent form submission
        }
        return true;
    }
    </script>
</body>
</html>
