<!DOCTYPE html>
<html>
<head>
    <title>Real-time Messages</title>
    <script>
        function fetchMessages() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'polling_handler.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText) {
                        var messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML += xhr.responseText + '<br>';
                    }
                    fetchMessages();  // Re-initiate long polling
                }
            };
            xhr.send();
        }

        // Start long polling when the page loads
        window.onload = function() {
            fetchMessages();
        };
    </script>
</head>
<body>
    <h1>Real-time Messages</h1>
    <div id="messages"></div>
</body>
</html>
