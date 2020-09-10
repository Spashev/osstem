<?php

namespace App\Amqp;

include('../../vendor/autoload.php');

use App\Events\SendNotification;
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

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);


$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

$messageBody = json_encode([
    'email' => 's.nurken92@gmail.com',
    'name' => 'Nurken',
    'subscribed' => true
]);

event(new SendNotification('hello'));

$message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($message, $exchange);

$channel->close();
$connection->close();