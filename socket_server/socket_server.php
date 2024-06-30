<?php
set_time_limit(0);

$address = '0.0.0.0';
$port = 12345;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);
socket_listen($sock);

$admin_clients = [];

while (true) {
    $client = socket_accept($sock);
    $input = socket_read($client, 1024);
    $data = json_decode($input, true);

    // Broadcast to all connected admin clients
    foreach ($admin_clients as $admin_client) {
        socket_write($admin_client, json_encode($data));
    }

    socket_close($client);
}
socket_close($sock);
?>