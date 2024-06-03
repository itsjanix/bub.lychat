<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db/db.php';

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$fetch_user_sql = "SELECT username, email FROM users WHERE user_id = ?";
$fetch_stmt = $conn->prepare($fetch_user_sql);
$fetch_stmt->bind_param("i", $user_id);
$fetch_stmt->execute();
$fetch_stmt->bind_result($username, $email);
$fetch_stmt->fetch();
$fetch_stmt->close();

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="templates/profile.css">
</head>
<body>
    <h2>Bub.ly Chat</h2>
    <div class="profile-container">
        <h2>Welcome, <?php echo $username; ?></h2>
        <div class="profile-info">
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
        </div>
    </div>
    <form action="profile.php" method="post">
        <button type="submit" name="logout" class="logout-button">Logout</button>
    </form>
    <form action="chatbox.php" method="post">
        <button type="submit" name="back" class="back-button">Back</button>
    </form>

</body>
</html>
