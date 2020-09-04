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
        $payments = Payment::with('manager', 'customer')->where('deadline', $to)->where('paid', 0)->limit(100)->get();
        dump('SMS notifications for payment: ', $payments->toArray()); # send sms
        if (Carbon::now()->isFriday() == 'True') {
            $to = Carbon::now()->subDay(3)->format('Y-m-d');
            // $from = Carbon::now()->subMonth()->format('Y-m-d');
            // $delay = Payment::with('manager', 'customer')->whereBeetwen('deadline', [$from, $to])->Where('remain', '<>', 0)->get();
            $delay = Payment::with('manager', 'customer')->where('deadline', $to)->where('remain', '<>', 0)->get();
            dump("SMS notifications for overdue clients: ", $delay->toArray()); # send sms
        }
    }
}