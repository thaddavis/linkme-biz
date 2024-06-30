<?php

$address = '127.0.0.1';
$port = 8920;
$null = NULL;

include 'functions.php';

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($sock === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit;
}

socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    exit;
}

if (socket_listen($sock) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    exit;
}

$members = [];
$connections = [$sock];

echo "Listening for new connections on port $port: " . "\n";

while (true) {
    $reads = $connections;
    $writes = $exceptions = null;
    socket_select($reads, $writes, $exceptions, 0);

    if (in_array($sock, $reads)) {
        $new_connection = socket_accept($sock);
        if ($new_connection === false) {
            echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
            continue;
        }

        $header = socket_read($new_connection, 1024);
        if ($header === false) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($new_connection)) . "\n";
            continue;
        }

        handshake($header, $new_connection, $address, $port);
        $connections[] = $new_connection;
        $reply = [
            "type" => "join",
            "sender" => "Server",
            "text" => "Enter name to join...\n"
        ];
        $reply = pack_data(json_encode($reply));
        socket_write($new_connection, $reply, strlen($reply));
        $firstIndex = array_search($sock, $reads);
        unset($reads[$firstIndex]);
    }

    foreach ($reads as $key => $value) {
        $data = @socket_read($value, 1024);

        if ($data === false) {
            $error_code = socket_last_error($value);
            $error_msg = socket_strerror($error_code);
            echo "socket_read() failed: [$error_code] $error_msg \n";
            socket_close($value);
            unset($connections[$key]);
            continue;
        }

        if (!empty($data)) {
            $message = unmask($data);
            $decoded_message = json_decode($message, true);
            if ($decoded_message) {
                if (isset($decoded_message['text'])) {
                    if ($decoded_message['type'] === 'join') {
                        $members[$key] = [
                            'name' => $decoded_message['sender'],
                            'connection' => $value
                        ];
                    }
                    $maskedMessage = pack_data($message);
                    foreach ($members as $mkey => $mvalue) {
                        socket_write($mvalue['connection'], $maskedMessage, strlen($maskedMessage));
                    }
                }
            }
        } else {
            echo "disconnected " . $key . "\n";
            unset($connections[$key]);
            if (array_key_exists($key, $members)) {
                $message = [
                    "type" => "left",
                    "sender" => "Server",
                    "text" => $members[$key]['name'] . " left the chat\n"
                ];
                $maskedMessage = pack_data(json_encode($message));
                unset($members[$key]);
                foreach ($members as $mkey => $mvalue) {
                    socket_write($mvalue['connection'], $maskedMessage, strlen($maskedMessage));
                }
            }
            socket_close($value);
        }
    }
}

socket_close($sock);