<?php
namespace App\Classes\Socket;

require_once '../vendor/autoload.php';


use Workerman\Worker;


$worker = new Worker('websocket://0.0.0.0:8001');

// 4 processes
$worker->count = 4;

// Emitted when data received
$worker->onConnect = function ($connection) {
    $connection->send("Hello World");
    \Workerman\Lib\Timer::add(1, function() use($connection) {
        $connection->send('Every sec new message');
    });
};

Worker::runAll();