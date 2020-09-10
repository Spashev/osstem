<?php

namespace App\Console\Commands;

use App\Jobs\NotificationJob;
use Illuminate\Console\Command;
use App\Repository\NotificationRepository;
use App\Repository\AmqpRepository;

class NotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command start notification service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $loop   = \React\EventLoop\Factory::create();
        // $pusher = new \App\Classes\Socket\Pusher;

        // // Listen for the web server to make a ZeroMQ push after an ajax request
        // $context = new \React\ZMQ\Context($loop);
        // $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        // $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        // $pull->on('message', array($pusher, 'onBlogEntry'));

        // // Set up our WebSocket server for clients wanting real-time updates
        // $webSock = new \React\Socket\Server('0.0.0.0:8080', $loop); // Binding to 0.0.0.0 means remotes can connect
        // $webServer = new \Ratchet\Server\IoServer(
        //     new \Ratchet\Http\HttpServer(
        //         new \Ratchet\WebSocket\WsServer(
        //             new \Ratchet\Wamp\WampServer(
        //                 $pusher
        //             )
        //         )
        //     ),
        //     $webSock
        // );

        // $loop->run();
        NotificationJob::dispatch();
        return 0;
    }
}