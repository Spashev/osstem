<?php

namespace App\Repository;

use App\Models\Payment;
use LazyElePHPant\Repository\Repository;

class NotificationRepository extends Repository
{
    public function model()
    {
        return Payment::class;
    }

    public static function start()
    {
    }
}