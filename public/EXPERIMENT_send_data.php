<?php
$host = '0.0.0.0';
$port = 12345;
$data = "Hello, Socket Server!";

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    die("socket_create() failed: " . socket_strerror(socket_last_error()));
}

// Connect to the socket server
if (socket_connect($socket, $host, $port) === false) {
    die("socket_connect() failed: " . socket_strerror(socket_last_error($socket)));
}

// Send data to the socket server
if (socket_write($socket, $data, strlen($data)) === false) {
    die("socket_write() failed: " . socket_strerror(socket_last_error($socket)));
}

echo "Data sent to server: $data\n";

// Close the socket
socket_close($socket);
?>
