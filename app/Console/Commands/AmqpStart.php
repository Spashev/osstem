<?php

namespace App\Console\Commands;

use App\Repository\AmqpRepository;
use Illuminate\Console\Command;

class AmqpStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amqp:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        AmqpRepository::sendNotifications();
        AmqpRepository::resiveNotifications();
        return 0;
    }
}