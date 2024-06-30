<?php
$host = '0.0.0.0';
$port = 12345;

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Could not create socket\n');
socket_connect($socket, $host, $port) or die('Could not connect to server\n');

while (true) {
    $input = @socket_read($socket, 1024, PHP_NORMAL_READ);
    if ($input !== false && !empty(trim($input))) {
        echo $input;
        break;
    }
    usleep(100000);  // Sleep for a short period to reduce CPU usage
}

socket_close($socket);
?>
