<?php
session_start();

// Include database connection
include 'db/db.php';

// Fetch messages from the database
$fetch_messages_sql = "SELECT users.username, messages.message_content, messages.created_at 
                       FROM messages 
                       JOIN users ON messages.user_id = users.user_id 
                       ORDER BY messages.created_at ASC"; // para sa dalom ma generate ang new chats
$result = $conn->query($fetch_messages_sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div><strong>" . htmlspecialchars($row['username']) . ":</strong> " . htmlspecialchars($row['message_content']) . " <em>(" . $row['created_at'] . ")</em></div>";
    }
} else {
    echo "No messages.";
}

$conn->close();
?>
