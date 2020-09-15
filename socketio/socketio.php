<?php
require_once '../vendor/autoload.php';

use Workerman\Worker;
use Carbon\Carbon;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$to = Carbon::now()->subDay(3)->format('Y-m-d');

$host = '127.0.0.1';
$db   = 'union_crm';
$user = 'admin';
$pass = '1q2w3e4r';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);


$stmt = $pdo->prepare("
    SELECT 
        `managers`.`in_charge` ,
        `customers`.`customer_id`,`customers`.`phone`,
        `customers`.`name`,`customers`.`region`,`customers`.`region_id`,
        `payments`.`contract_id`,`payments`.`seq`, `payments`.`amount`,
        `payments`.`payment_date`, `payments`.`deadline`,`payments`.`paid`,
        `payments`.`remain`, `contracts`.`contract_no` 
    FROM 
        `payments` 
    LEFT JOIN 
        `contracts` 
    ON 
        `payments`.`contract_id` = `contracts`.`id` 
    LEFT JOIN 
        `managers` 
    ON 
        `contracts`.`manager_id` = `managers`.`id` 
    LEFT JOIN 
        `customers` 
    ON 
        `contracts`.`customer_id` = `customers`.`id`
    WHERE 
        `remain` <> 0 
    AND 
        `deadline` = :deadline
");

$stmt->execute([
    ':deadline' => $to 
]);

$result = $stmt->fetchAll(PDO::FETCH_OBJ);
$result = json_encode($result, JSON_UNESCAPED_UNICODE);

dump($result, $to);

// $connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest','guest');
// $channel = $connection->channel();

// $channel->queue_declare('notification', false, false, false, false); // uniq queue-name

// $channel->exchange_declare('notification', 'topic', false, false, false);

// $channel->queue_bind('notification', 'topic_logs', 'test_bind_key');

// $msg = new AMQPMessage('Hello World!');

// $channel->basic_publish($msg, 'notification', 'test_bind_key');

$worker = new Worker('websocket://0.0.0.0:8001');
// 4 processes
$worker->count = 4;
// Emitted when data received
$worker->onConnect = function ($connection) use(&$result){
    $connection->send($result);
    \Workerman\Lib\Timer::add(1, function() use($connection, &$result) {
        $connection->emit($result);
    });
};
Worker::runAll();

