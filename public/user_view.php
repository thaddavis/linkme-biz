<?php
function redirect($url)
{
    header('Location: '.$url);
    exit();
}

$servername = "db";
$username = "user";
$password = "password";
$dbname = "link_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$link = $_GET['link'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("INSERT INTO link_access_logs (link_id) VALUES ((SELECT id FROM links WHERE link=?))");
    $stmt->bind_param("s", $link);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO submissions (link_id, name, email, phone) VALUES ((SELECT id FROM links WHERE link=?), ?, ?, ?)");
    $stmt->bind_param("ssss", $link, $name, $email, $phone);
    $stmt->execute();
    $stmt->close();
    
    redirect('http://localhost:8000/admin_view.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User View</title>
</head>
<body>
    <h1>Submit Your Information</h1>
    <form id="user-view-form" method="POST">
        <label>Name: <input id="name" type="text" name="name" required></label><br>
        <label>Email: <input id="email" type="email" name="email" required></label><br>
        <label>Phone: <input id="phone" type="text" name="phone" required></label><br>
        <button type="submit">Submit</button>
    </form>
    <script src="user_view.js"></script>
</body>
</html>