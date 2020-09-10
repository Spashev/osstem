<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use LazyElePHPant\Repository\Repository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class AmqpRepository extends Repository
{
    const HOST = "localhost";
    const PORT = 5672;
    const USER = 'guest';
    const PASS = 'guest';
    const VHOST = '/';

    public function model()
    {
        return Model::class;
    }
    public static function sendNotifications()
    {
        $exchange = 'router';
        $queue = 'notification';

        $connection = new AMQPStreamConnection(
            AmqpRepository::HOST,
            AmqpRepository::PORT,
            AmqpRepository::USER,
            AmqpRepository::PASS,
            AmqpRepository::VHOST
        );
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);

        $messageBody = json_encode([
            'email' => 's.nurken92@gmail.com',
            'name' => 'Nurken',
            'subscribed' => true
        ]);
        $message = new AMQPMessage($messageBody, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $channel->basic_publish($message, $exchange);

        // $channel->close();
        // $connection->close();
    }

    public static function resiveNotifications()
    {
        $exchange = 'router';
        $queue = 'notification';
        $consumerTag = 'notification.consumer';

        $connection = new AMQPStreamConnection(
            AmqpRepository::HOST,
            AmqpRepository::PORT,
            AmqpRepository::USER,
            AmqpRepository::PASS,
            AmqpRepository::VHOST
        );
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange);
        $process_message = function ($message) {
            echo $message->body;
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
    }
}