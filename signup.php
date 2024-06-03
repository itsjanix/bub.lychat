<?php
include 'db/db.php'; // Include the database connection file

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    // Check if the username already exists
    $check_username_stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    if ($check_username_stmt) {
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_result = $check_username_stmt->get_result();
        if ($check_username_result->num_rows > 0) {
            // Username already exists
            $status = "Username already exists. Please choose a different username.";
            $check_username_stmt->close();
        } else {
            $check_username_stmt->close();

            // Check if the email already exists
            $check_email_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            if ($check_email_stmt) {
                $check_email_stmt->bind_param("s", $email);
                $check_email_stmt->execute();
                $check_email_result = $check_email_stmt->get_result();
                if ($check_email_result->num_rows > 0) {
                    // Email already exists
                    $status = "Email already exists. Please choose a different email.";
                    $check_email_stmt->close();
                } else {
                    $check_email_stmt->close();

                    // Prepare the SQL statement to insert the new user
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("sss", $username, $email, $password);
                        if ($stmt->execute()) {
                            $status = "User registered successfully.";
                        } else {
                            $status = "Failed to register user.";
                        }
                        $stmt->close();
                    } else {
                        $status = "Failed to prepare the SQL statement.";
                    }
                }
            } else {
                $status = "Failed to prepare the email check SQL statement.";
            }
        }
    } else {
        $status = "Failed to prepare the username check SQL statement.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bub.ly Chat - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="templates/signup.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form">
                <h1>Bub.ly Chat</h1>
                <h2>Sign Up</h2>

                <form method="post" action="signup.php">
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required>
                    <br><br>
                    <input type="submit" value="Sign Up">
                </form> 

                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>

        <div class="output">
            <?php
                if (!empty($status)) {
                    echo "<p>$status</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
