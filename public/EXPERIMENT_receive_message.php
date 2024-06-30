<?php
session_start();

echo "--- --- --- --- ---";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $_SESSION['message'] = $message; // Store the message in a session variable
    echo "Message received and stored: $message";
    exit; // Ensure the message is processed only once
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : 'No message received.';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Message Receiver</title>
</head>
<body>
    <h1>Message Receiver</h1>
    <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>
