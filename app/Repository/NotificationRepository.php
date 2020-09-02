<?php

namespace App\Repository;

use App\Models\Payment;
use Carbon\Carbon;
use LazyElePHPant\Repository\Repository;

class NotificationRepository extends Repository
{
    public function model()
    {
        return Payment::class;
    }

    public static function start()
    {
        $to = Carbon::now()->addDays('3')->format('Y-m-d');
        $payments = Payment::with('manager','customer')->where('deadline', $to)->where('paid',0)->limit(100)->get();
        dump($payments->toArray());
        if(Carbon::now()->isMonday() == 'True') {
            $to = Carbon::now()->format('Y-m-d');
            $from = Carbon::now()->subMonth()->format('Y-m-d');
            $delay = Payment::with('manager','customer')->whereBeetwen('deadline', [$from, $to])->Where('remain','<>',0)->get();
            dump($delay->toArray());
            # send sms
        }
    }
}
