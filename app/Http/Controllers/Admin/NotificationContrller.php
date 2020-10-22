<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationContrller extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('notify.index');
    }

    /**
     * getData
     *
     * @return void
     */
    public function getData()
    {
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $payments = Payment::with('customer')->whereBetween('deadline', [$start, $end])->get()->unique('customer_id');
        $result = [];
        foreach ($payments as $payment) {
            $customer = $payment->customer;
            if (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
                $result[] = [
                    'text' => $customer->name,
                    'id' => $customer->customer_id,
                ];
            }
        }

        $regions = Region::get(['name', 'id']);
        $regions = $regions->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'name') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        return response()->json(
            [
                'customers' => $result,
                'regions' => $regions
            ],
            200
        );
    }

    /**
     * region
     *
     * @param  mixed $region
     * @return void
     */
    public function region(Region $region)
    {
        $now = Carbon::now()->format('Y-m-d');
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $customers = $region->customers;
        $customer_result = [];
        foreach ($customers as $customer) {
            if (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
                $item = Payment::with('contract')->where('customer_id', $customer->id)->whereBetween('deadline', [$start, $end])->where('remain', '>', 0)->get();
                if (count($item) > 0 && count($item->last()->notifications) == 0) {
                    $customer_result[] = [
                        'text' => $customer->name,
                        'id' => $customer->id,
                        'customer_code' => $customer->customer_id,
                        'total_remain' => $item->sum('remain'),
                        'total_paid' => $item->sum('paid'),
                        'sms_status' => $customer->sms_status
                    ];
                }
            }
        }
        if (count($customer_result) == 0) {
            $customer_result = [
                'msg' => 'there are no unpaid customers in this region'
            ];
        }
        return response()->json(
            $customer_result,
            200
        );
    }

    /**
     * contractData
     *
     * @param  mixed $request
     * @return void
     */
    public function contractData(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $customer = Customer::where('customer_id', $request->customer_id)->first();
        $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->whereBetween('deadline', [$start, $end])->get();
        $result = [];
        foreach ($payments as $key => $payment) {
            if (count($payment->notifications) == 0) {
                $result[] = [
                    'payment_id' => $payment->id,
                    'id' => $key + 1,
                    'contract_no' => $payment->contract->contract_no,
                    'total_remain' => $payment->remain,
                    'total_paid' => $payment->paid,
                    'deadline' => Str::substr($payment->deadline, 0, 11),
                    'sms_status' => $payment->sms_status
                ];
            }
        }
        if (count($result) == 0) {
            $result = [
                'msg' => 'there are no unpaid customers in this region'
            ];
        }
        return response()->json(
            $result,
            200
        );
    }

    /**
     * smsStatus
     *
     * @param  mixed $request
     * @return void
     */
    public function smsStatus(Request $request)
    {
        $result = [];
        if ($request->type == 'contract') {
            $contract = Contract::where('contract_no', $request->contract_code)->first();
            $payments = $contract->payments;
            foreach ($payments as $payment) {
                $payment->sms_status = $payment->sms_status == 'on' ? 'off' : 'on';
                $payment->save();
            }
            $result = [
                'msg' => 'Customer, ' . $contract->customer->name . ', status changed: ' . $payment->sms_status
            ];

            return response()->json(
                $result,
                200
            );
        } else {
            if ($request->customer_code != "null") {
                $status_list = explode(',', $request->customer_code);
                foreach ($status_list as $status) {
                    if ($status != "") {
                        $customer = Customer::where('customer_id', $status)->first();
                        $customer->sms_status = $customer->sms_status == 'on' ? 'off' : 'on';
                        $customer->save();
                    }
                }
            }
        }
    }

    /**
     * sendSms
     *
     * @param  mixed $request
     * @return void
     */
    public function sendSms(Request $request)
    {
        $contract = Contract::where('contract_no', $request->contract_no)->first();
        $customer = $contract->customer;
        $to = Carbon::now()->addDays('3')->format('Y-m-d');
        $payments = $customer->notifyPayments;
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on';
        });
        if (count($payments) > 0 and (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
            dump('За 3 дня оплаты смс');
            $sum = 0;
            $to_sum = 0;
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            foreach ($payments as $payment) {
                $payment_percent = Payment::where('contract_id', $payment->contract_id)->where('remain', '>', 0)->where('deadline', '<=', $to)->get();
                if ($payment_percent->count() == 1) {
                    $sum = $payment_percent->first()->remain;
                } else {
                    foreach ($payment_percent as $payment) {
                        if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $sum += $payment->remain;
                        } else {
                            $delayInDays = Carbon::createFromDate($payment->deadline)->addMonth()->diffInDays($payment->deadline);
                            if ($payment->percent == 0) {
                                $sum += $payment->remain;
                            } else {
                                $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays + $payment->remain;
                            }
                        }
                    }
                    $sum += $payment_percent->last()->remian;
                }
                // dump($sum, $payment->deadline, $payment->hash);
            }
            if ($sum != 0) {
                $message = "Уважаемый %s!,\n Просим оплатить %sтг. в срок %s.";
                $result = [
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'amount' => $sum,
                    'deadline' => Str::substr($to, 0, 10),
                ];
                $text = sprintf($message, $result['customer_name'], $result['amount'], $result['deadline']);
                dump($text);
                info($text);
                // $sms = new SmsService();
                // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                // $status = true;
                // if ($status) {
                //     $customer->notifications()->create([
                //         'customer_id' => $customer->id,
                //         'customer_name' => $result['customer_name'],
                //         'contract_no' => $sum,
                //         'phone_number' => $result['customer_phone'],
                //         'amount' => $result['amount'],
                //         'amount_percent' => 0,
                //         'status' => 0
                //     ]);
                //     $customer->save();
                // }
            }
        }
    }
}