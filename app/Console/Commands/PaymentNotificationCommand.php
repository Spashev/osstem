<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Smsc\SmsService;

class PaymentNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start payment notification service';

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
        $to = Carbon::now()->subDay(3)->format('Y-m-d');
        $to = Str::substr($to, 0, 10);
        dump($to);
        $data = Payment::with('contract')->where('deadline',$to)->where('remain', '<>', 0)->get();
        // $sms = new SmsService();
        // $send = $sms->send_sms($phones = '87474646208',$message = 'sms from server', $sender = 'Spashev');
        // dd($send);
        dump("SMS notifications for overdue clients: ", $data->toArray()); # send sms
        return 0;
    }
}