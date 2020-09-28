<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function get_data()
    {
        // return Payment::with('contract')->paginate(20);
        return Contract::with('payments','customer','manager')->get();
        // $result = [];
        // foreach($payments as $payment) {
        //     $result[] = [
        //         'manager_name' => $payment->contract->manager->name,
        //         'in_charge' => $payment->contract->manager->in_charge,
        //         'region' => $payment->contract->manager->region,
        //         'customer_name' => $payment->contract->customer->name,
        //         'in_cpercentharge' => $payment->percent,
        //         'contract_no' => $payment->contract->contract_no,
        //         'seq' => $payment->seq,
        //         'amount' => $payment->amount,
        //         'payment_date' => $payment->payemnt_date,
        //         'deadline' => $payment->deadline,
        //         'paid' => $payment->paid,
        //         'remain' => $payment->remain
        //     ];
        // }
        // return $result;
    }
}
