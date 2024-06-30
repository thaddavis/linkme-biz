<?php
$host = "0.0.0.0";
$port = 12345;

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die('Could not create socket');
$result = socket_connect($socket, $host, $port) or die('Could not connect to server');

// Read message from the socket
$msg = socket_read($socket, 1024);
echo trim($msg);

socket_close($socket);
?>