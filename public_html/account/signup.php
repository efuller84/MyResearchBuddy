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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username, password, email, and userType are set
    if (isset($_POST['username'], $_POST['password'], $_POST['userType'], $_POST['email'], $_POST['name'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $rePassword = trim($_POST['rePassword']);
        $email = trim($_POST['email']);
        $userType = trim($_POST['userType']);
        $name = trim($_POST['name']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Ensure that none of the fields are empty
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

                    $stmt = $conn->prepare("INSERT INTO Students (s_username, s_name, s_password, s_email, field_of_research) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $username, $name, $hashed_password, $email, $tags_str);

                    if ($stmt->execute()) {
                        $_SESSION['username'] = $username;
                        $_SESSION['userType'] = $usertype;
                        header("Location: ../dashboard/dashboard.php");
                        exit();
                    } else {
                        $error = "Error signing up as student. Please try again.";
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
                    $stmt->bind_param("ssss", $username, $name, $hashed_password, $email);

                    if ($stmt->execute()) {
                        echo "Professor sign-up successful!";
                    } else {
                        $error = "Error signing up as professor. Please try again.";
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
        $error = "Username, Password, Email, Name, and User Type fields are required.";
    }

    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <style>
    .submit-btn {
        margin-top: 60px; /* Adds space above the Sign Up button */
    }

    /* Other CSS remains the same */
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

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr); 
        gap: 10px;
    }
</style>



</head>
<body>
    <h2>Sign Up</h2>
    <form action="" method="post" onsubmit="return validateForm()">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br><br>

        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>

        <label for="rePassword">Re-enter Password:</label>
        <input type="password" name="rePassword" id="rePassword" required>
        <br><br>

       <!-- Buttons to select whether the user is a Student or Professor -->
        <label>Are you a:</label>
        <br><br>
        <input type="radio" id="student" name="userType" value="student" onclick="toggleTagsDropdown()">
        <label for="student">Student</label>

        <br><br>

        <input type="radio" id="professor" name="userType" value="professor" onclick="toggleTagsDropdown()">
        <label for="professor">Professor</label>

    <br><br>

        <!-- Tags dropdown for students (initially hidden) -->
        <div class="dropdown" id="tagsDropdown" style="display:none;">
            <button>Select Items</button>
            <div class="dropdown-content">
                <div class="checkbox-grid">
                    <label><input type="checkbox" name="tags[]" value="1"> AI</label>
                    <label><input type="checkbox" name="tags[]" value="2"> Machine Learning</label>
                    <label><input type="checkbox" name="tags[]" value="3"> Data Science</label>
                    <label><input type="checkbox" name="tags[]" value="4"> Robotics</label>
                    <label><input type="checkbox" name="tags[]" value="5"> Quantum Computing</label>
                    <!-- Add more tags as necessary -->
                </div>
            </div>
        </div>

        <br><br>
        <input type="submit" value="Sign Up">
    </form>

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
    </script>
    <script>
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

    <br>
    <p>If you have an account, please <a href="signin.php">sign in</a>.</p>
</body>
</html>
