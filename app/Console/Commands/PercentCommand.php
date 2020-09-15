<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PercentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:percent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start bot for payment percent';

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
        $now = Str::substr(Carbon::now(), 0, 10);
        // $payments = Payment::with('contract')->where('payment_date', $now)->where('paid', 0)->where('remain', '<>', 0)->get();
        // dump($payments);
        // foreach ($payments as $payment) {
        //     $minusDays = intval(Str::substr($now, 8, 10)) - intval(Str::substr($payment->payment_date, 8, 10));
        //     $amount = ((($payment->percent * $payment->amount) / 100) * $minusDays) + $payment->amount;
        //     $payment->amount_percent = $amount;
        //     $payment->delay = 1;
        //     $payment->save();
        // }
        // dump($payments->toArray());
        // info('Percent: ', $payments->toArray());

        // $remainDay = Carbon::now()->subDays(10)->format('Y-m-d');
        $subMonth = Carbon::now()->subMonth()->format('Y-m-d');
        dump($now, $subMonth);
        $payments = Payment::with('contract')->whereBetween('deadline', [$subMonth, $now])->where('paid', 0)->where('remain','<>', 0)->get();
        dump($payments);
        foreach ($payments as $payment) {
            $minusDays = intval(Str::substr($now, 8, 10)) - intval(Str::substr($payment->payment_date, 8, 10));
            $amount = ((($payment->percent * $payment->amount) / 100) * $minusDays) + $payment->amount;
            $payment->amount_percent = $amount;
            $payment->delay = 1;
            $payment->save();
            dump($payment);
        }
        return 0;
    }
}