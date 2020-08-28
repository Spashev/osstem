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
        $date = Carbon::now()->subDays('3');
        dump($date);
        $payments = ModelsPayment::with('manager','customer')->where('payment_date','<',$date)->limit(100)->get();
        dump($payments->toArray());
    }
}
