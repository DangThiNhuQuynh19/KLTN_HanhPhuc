<?php
require_once('vendor/autoload.php');
require_once('Chat.php'); 

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

// Khởi động server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080 // Cổng WebSocket
);

echo "WebSocket server running on ws://localhost:8080\n";
$server->run();
