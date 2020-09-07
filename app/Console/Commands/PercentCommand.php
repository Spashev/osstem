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
    protected $signature = 'percent:start';

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
        $monthStart = Str::substr(Carbon::now()->startOfMonth(), 0, 10);
        $payments = Payment::whereBetween('payment_date', [$monthStart, $now])->where('remain', '<>', 0)->get();
        foreach ($payments as $payment) {
            $minusDays = intval(Str::substr($payment->payment_date, 8, 10)) - intval(Str::substr($now, 8, 10));
            $amount = ((($payment->percent * $payment->amount) / 100)) + $payment->amount;
            $payment->amount_percent = $amount;
            $payment->save();
        }
        info('Percent: ', $payments->toArray());
        return 0;
    }
}