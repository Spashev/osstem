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
        NotificationJob::dispatch();
        return 0;
    }
}