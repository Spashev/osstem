<?php

namespace App\Console\Commands;

use App\Jobs\PaymentJob;
use Illuminate\Console\Command;

class PaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start notification bot';

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
        PaymentJob::dispatch();
        return 0;
    }
}