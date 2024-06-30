<?php
$host = "0.0.0.0";  // Listen on all IPs
$port = 12345;      // Port to listen on

// Create a TCP/IP socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

// Allow address reuse
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1) or die("Could not set socket options\n");

// Bind the socket to the address and port
socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

// Start listening for connections
socket_listen($socket, 10) or die("Could not set up socket listener\n");

// Set the socket to non-blocking mode
socket_set_nonblock($socket);

$clients = [$socket];  // Array to hold client sockets

while (true) {
    $read = $clients;
    $write = null;
    $except = null;

    // Wait for activity on any of the sockets
    if (socket_select($read, $write, $except, 0, 10) < 1) {
        usleep(100000);  // Sleep for a short period to reduce CPU usage
        continue;
    }

    // Check if there is a new connection
    if (in_array($socket, $read)) {
        $new_socket = socket_accept($socket);
        if ($new_socket) {
            // Set the new socket to non-blocking mode
            socket_set_nonblock($new_socket);
            $clients[] = $new_socket;
            echo "New client connected.\n";
        }
        unset($read[array_search($socket, $read)]);
    }

    // Handle communication with existing clients
    foreach ($read as $client_socket) {
        $input = @socket_read($client_socket, 1024, PHP_NORMAL_READ);
        if ($input === false || $input === "") {
            // Remove client from the list and close the socket
            unset($clients[array_search($client_socket, $clients)]);
            socket_close($client_socket);
            echo "Client disconnected.\n";
            continue;
        }

        // Trim and handle the input
        $input = trim($input);
        if (!empty($input)) {
            echo "Received message: $input\n";
            // Broadcast the message to all clients except the sender and the server socket
            foreach ($clients as $send_socket) {
                if ($send_socket != $socket && $send_socket != $client_socket) {
                    $write_result = @socket_write($send_socket, $input, strlen($input));
                    if ($write_result === false) {
                        // Remove client from the list and close the socket
                        unset($clients[array_search($send_socket, $clients)]);
                        socket_close($send_socket);
                        echo "Client disconnected due to write error.\n";
                    }
                }
            }
        }
    }
}

// Close the server socket
socket_close($socket);
?>
