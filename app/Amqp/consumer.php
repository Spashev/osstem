<?php

namespace App\Amqp;

include('../../vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

const HOST = "127.0.0.1";
const PORT = 5672;
const USER = 'guest';
const PASS = 'guest';
const VHOST = '/';

$exchange = 'router';
$queue = 'msgs';
$consumerTag = 'consumer';

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

$process_message = function (AMQPMessage $message) {

    $messageBody = json_decode($message->body);
    $email = $messageBody->email;
    dump($messageBody);
    file_put_contents($email . '.json', $message->body);

    $message->ack();

    if ($message->body === 'quit') {
        $message->getChannel()->basic_cancel($message->getConsumerTag());
    }
};

$channel->basic_consume($queue, $consumerTag, false, false, false, false, $process_message);

$shutdown = function ($channel, $connection) {
    $channel->close();
    $connection->close();
};

register_shutdown_function($shutdown, $channel, $connection);

while ($channel->is_consuming()) {
    $channel->wait();
}