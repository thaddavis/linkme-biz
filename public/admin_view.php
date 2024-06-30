<?php
$servername = "db";
$username = "user";
$password = "password";
$dbname = "link_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unique_link = uniqid('link_', true);
    $stmt = $conn->prepare("INSERT INTO links (link) VALUES (?)");
    $stmt->bind_param("s", $unique_link);
    $stmt->execute();
    $stmt->close();
    echo "Generated link: <a href='user_view.php?link=$unique_link'>Click here</a>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin View</title>
</head>
<body>
    <h1>Generate a Link</h1>
    <form method="POST">
        <button type="submit">Generate Link</button>
    </form>

    <h2>Active Links</h2>
    <ul>
    <?php
    $query = "
        SELECT links.id, links.link, 
               COUNT(DISTINCT submissions.id) AS submission_count,
               COUNT(DISTINCT link_access_logs.id) AS access_count
        FROM links
        LEFT JOIN submissions ON links.id = submissions.link_id
        LEFT JOIN link_access_logs ON links.id = link_access_logs.link_id
        GROUP BY links.id, links.link
    ";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<li><a href='user_view.php?link=" . $row['link'] . "'>" . $row['link'] . "</a> - Submissions: " . $row['submission_count'] . " - Accesses: " . $row['access_count'] . "</li>";
    }
    $conn->close();
    ?>
    </ul>
</body>
</html>