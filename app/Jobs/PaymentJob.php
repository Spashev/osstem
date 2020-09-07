<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Carbon::now()->isFriday() == 'True') {
            $to = Carbon::now()->subDay(3)->format('Y-m-d');
            $delay = Payment::with('manager', 'customer')->where('deadline', $to)->where('remain', '<>', 0)->get();
            dump("SMS notifications for overdue clients: ", $delay->toArray()); # send sms
        }
    }
}