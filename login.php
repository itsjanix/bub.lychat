<?php
session_start();
include 'db/db.php'; // Include the database connection file

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username=?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                header("Location: chatbox.php");
                exit();
            } else {
                $status = "Invalid username or password.";
            }
        } else {
            $status = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        $status = "Failed to prepare the SQL statement.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bub.ly Chat - Login</title>
    <link rel="stylesheet" type="text/css" href="templates/login.css">
</head>
<body>
    <div class="container">
        <div class="form">
            <h1>Bub.ly Chat</h1>
            <h2>Login</h2>

            <form method="post" action="login.php">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required>
                <br><br>
                <input type="submit" value="Login">
            </form> 

            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
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
