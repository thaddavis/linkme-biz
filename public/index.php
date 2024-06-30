<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="chat-box">
            <div class="messages"></div>
            <form action="" class="join-form">
                <input type="text" name="sender" id="sender" placeholder="Enter name">
                <button type="submit">Join Chat</button>
            </form>
            <form action="" method="post" class="msg-form hidden">
                <input type="text" name="msg" id="msg" placeholder="Write message">
                <button type="submit">Send</button>
            </form>
            <form action="" class="close-form hidden"> 
                <button type="submit">End Chat</button>
            </form>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html> -->

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
    <script src="user_view.js"></script>
</body>
</html>