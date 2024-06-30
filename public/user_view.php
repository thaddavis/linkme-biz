<?php
$servername = "db";
$username = "user";
$password = "password";
$dbname = "link_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$link = $_GET['link'];

// Log link access
$stmt = $conn->prepare("INSERT INTO link_access_logs (link_id) VALUES ((SELECT id FROM links WHERE link=?))");
$stmt->bind_param("s", $link);
$stmt->execute();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO submissions (link_id, name, email, phone) VALUES ((SELECT id FROM links WHERE link=?), ?, ?, ?)");
    $stmt->bind_param("ssss", $link, $name, $email, $phone);
    $stmt->execute();
    $stmt->close();
    
    // Sending data to admin view via socket (simplified)
    $socket = fsockopen("127.0.0.1", 12345);
    fwrite($socket, json_encode(['link' => $link, 'name' => $name, 'email' => $email, 'phone' => $phone]));
    fclose($socket);
    
    echo "Thank you for your submission!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User View</title>
</head>
<body>
    <h1>Submit Your Information</h1>
    <form method="POST">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Phone: <input type="text" name="phone" required></label><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>