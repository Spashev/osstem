<?php

namespace App\Repository;

use App\Models\Payment as ModelsPayment;
use App\Payment;
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
        $to = Carbon::now()->addDays('4');
        $from = Carbon::now();
        dump($from, $to);
        $payments = ModelsPayment::with('manager','customer')->whereBetween('payment_date',[$from, $to])->limit(100)->get();
        dump($payments->toArray());
    }
}
