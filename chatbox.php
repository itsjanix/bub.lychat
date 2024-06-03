<?php
session_start();

// Include database connection
include 'db/db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted to post a new message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_message'])) {
    // Get user ID and message content
    $user_id = $_SESSION['user_id'];
    $message_content = $_POST['message_content'];

    // Insert message into database
    $insert_message_sql = "INSERT INTO messages (user_id, message_content, created_at) VALUES (?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_message_sql);
    $insert_stmt->bind_param("is", $user_id, $message_content);

    if (!$insert_stmt->execute()) {
        echo "Error adding message: " . $insert_stmt->error;
    }

    $insert_stmt->close();
}

// Fetch messages from the database
function fetchMessages($conn) {
    $sql = "SELECT u.username, m.message_content, m.created_at FROM messages m JOIN users u ON m.user_id = u.user_id ORDER BY m.created_at ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="message sender">';
            echo '<strong>' . htmlspecialchars($row['username']) . '</strong>';
            echo '<p>' . htmlspecialchars($row['message_content']) . '</p>';
            echo '<em>' . htmlspecialchars($row['created_at']) . '</em>';
            echo '</div>';
        }
    } else {
        echo '<p>No messages yet.</p>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bub.ly Chat</title>
    <link rel="stylesheet" type="text/css" href="templates/chatboxStyle.css">
    <link rel="stylesheet" type="text/css" href="templates/profile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Inline styles for immediate effect, consider moving these to chatboxStyle.css */
        .messageForm textarea {
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Bub.ly Chat</h2>
    <div id="messages">
        <?php fetchMessages($conn); ?>
    </div>
    <form class="messageForm" id="messageForm" action="chatbox.php" method="post">
        <label for="message_content">Your Message:</label><br>
        <textarea id="message_content" name="message_content" rows="4" cols="50" required></textarea><br>
        <button type="submit" name="submit_message">Send</button>
    </form>

    <script>
    $(document).ready(function(){
        // Function to load messages and scroll to the bottom
        function loadMessages() {
            $.ajax({
                type: "GET",
                url: "get_messages.php", // PHP script to fetch messages
                success: function(data) {
                    $("#messages").html(data);
                    scrollToBottom(); // Scroll to the bottom after messages are loaded
                }
            });
        }

        // Function to scroll to the bottom of the chat
        function scrollToBottom() {
            $("#messages").scrollTop($("#messages")[0].scrollHeight);
        }

        // Load messages initially
        loadMessages();

        // Reload messages every 5 seconds
        setInterval(loadMessages, 5000); // Adjust the interval as needed

        // Automatically adjust textarea height based on content
        $('#message_content').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>


    <a href="profile.php" class="profile-link">Profile</a>
</body>
</html>

<?php
// Close the connection at the end of the script
$conn->close();
?>
