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
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
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
        $customers = $region->customers;
        $now = Carbon::now()->format('Y-m-d');
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        $customer_result = [];
        foreach ($customers as $customer) {
            if ($customer->sms_status == 'on' and $customer->notifications->count() == 0) {
                $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->whereBetween('deadline', [$start, $end])->where('sms_status', 'on')->get();
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if (count($payments) > 0 and $payments->last() !== null) {
                    if ($customer->notifications->count() == 0) {
                        $customer_result[] = [
                            'text' => $customer->name,
                            'id' => $customer->id,
                            'customer_code' => $customer->customer_id,
                            'total_remain' => $payments->last()->getCustomerRemain(),
                            'total_paid' => $payments->last()->getCustomerPaid(),
                            'sms_status' => $customer->sms_status
                        ];
                    } else if ($customer->notifications->count() > 0) {
                        $notify_flag = true;
                        foreach ($customer->notifications as $notify) {
                            if ($notify->created_at->between($start, $end) and $notify->status == 0) {
                                $notify_flag = false;
                                break;
                            }
                        }
                        if ($notify_flag) {
                            $customer_result[] = [
                                'text' => $customer->name,
                                'id' => $customer->id,
                                'customer_code' => $customer->customer_id,
                                'total_remain' => $payments->last()->getCustomerRemain(),
                                'total_paid' => $payments->last()->getCustomerPaid(),
                                'sms_status' => $customer->sms_status
                            ];
                        }
                    }
                }
            } else {
                $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->whereBetween('deadline', [$start, $end])->where('sms_status', 'on')->get();
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if (count($payments) > 0 and $payments->last() !== null) {
                    if ($customer->notifications->count() == 0) {
                        $customer_result[] = [
                            'text' => $customer->name,
                            'id' => $customer->id,
                            'customer_code' => $customer->customer_id,
                            'total_remain' => $payments->last()->getCustomerRemain(),
                            'total_paid' => $payments->last()->getCustomerPaid(),
                            'sms_status' => $customer->sms_status
                        ];
                    } else if ($customer->notifications->count() > 0) {
                        $notify_flag = true;
                        foreach ($customer->notifications as $notify) {
                            if ($notify->created_at->between($start, $end) and $notify->status == 0) {
                                $notify_flag = false;
                                break;
                            }
                        }
                        if ($notify_flag) {
                            $customer_result[] = [
                                'text' => $customer->name,
                                'id' => $customer->id,
                                'customer_code' => $customer->customer_id,
                                'total_remain' => $payments->last()->getCustomerRemain(),
                                'total_paid' => $payments->last()->getCustomerPaid(),
                                'sms_status' => $customer->sms_status
                            ];
                        }
                    }
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
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        $result = [];
        foreach ($payments as $key => $payment) {
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
            $payments = $payments->filter(function ($value, $key) {
                return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
            });
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
     * sendSms уведомления о оплате по клиенту
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
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        if (count($payments) > 0 and (count($customer->notifications) == 0 or $customer->notifications->last()->status == 1 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
            dump('За 3 дня оплаты смс');
            $sum = 0;
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            foreach ($payments as $payment) {
                if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                    $sum += $payment->remain;
                } else {
                    $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                    if ($payment->percent == 0) {
                        $sum += $payment->remain;
                    } else {
                        $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays + $payment->remain;
                    }
                    dump($delayInDays);
                }
                dump($sum, $payment->deadline);
            }
            if ($sum != 0) {
                $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                $result = [
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'amount' => $sum,
                    'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                ];
                $text = sprintf($message, $result['amount'], $payment->deadline);
                dump($text);
                info($text);
                // $sms = new SmsService();
                // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                $status = true;
                if ($status) {
                    $customer->notifications()->create([
                        'customer_id' => $customer->id,
                        'payment_id' => $payment->id,
                        'customer_name' => $result['customer_name'],
                        'contract_no' => $payment->contract->contract_no,
                        'phone_number' => $result['customer_phone'],
                        'amount' => $result['amount'],
                        'amount_percent' => 0,
                        'status' => 0
                    ]);
                    $customer->save();
                }
            }
        }
    }

    /**
     * sendRegionSms уведомления о оплате по Региону
     *
     * @param  mixed $region
     * @return void
     */
    public function sendRegionSms(Region $region)
    {
        $customers = $region->customers;
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        foreach ($customers as $customer) {
            if ($customer->sms_status == 'on' and $customer->notifications->count() == 0) {
                $payments = $customer->deadlinePayments;
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if ($payments->count() > 0) {
                    $sum = 0;
                    $total_remian = 0;
                    foreach ($payments as $payment) {
                        $start = new Carbon('first day of this month');
                        $end = new Carbon('last day of this month');
                        if ($payment->first()->percent == 0) {
                            $sum += $payment->remain;
                            $total_remian = 0;
                        } else if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        } else {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        }
                        dump($delayInDays);
                    }
                    dump('First: ', $sum, $total_remian);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum + $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['amount'], $result['deadline']);
                        info($text);
                        dump($text);
                        // $sms = new SmsService();
                        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                        $status = true;
                        if ($status) {
                            $customer->notifications()->create([
                                'customer_id' => $customer->id,
                                'payment_id' => $payment->id,
                                'customer_name' => $result['customer_name'],
                                'contract_no' => $payment->contract->contract_no,
                                'phone_number' => $result['customer_phone'],
                                'amount' => $result['amount'],
                                'amount_percent' => 0,
                                'status' => 0
                            ]);
                            $customer->save();
                        }
                    }
                }
            } else if ($customer->sms_status == 'on' and $customer->notifications->count() > 0) {
                $notify_flag = true;
                foreach ($customer->notifications as $notify) {
                    if ($notify->created_at->between($start, $end) and $notify->status == 0) {
                        $notify_flag = false;
                        break;
                    }
                }
                if ($notify_flag) {
                    $total_remian = 0;
                    $payments = $customer->deadlinePayments;
                    $payments = $payments->filter(function ($value, $key) {
                        return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                    });
                    $sum = 0;
                    foreach ($payments as $payment) {
                        $start = new Carbon('first day of this month');
                        $end = new Carbon('last day of this month');
                        if ($payment->first()->percent == 0) {
                            $sum += $payment->remain;
                            $total_remian = 0;
                        } else if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        } else {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays;
                            $total_remian += $payment->remain;
                        }
                        dump($delayInDays);
                    }
                    dump('Second', $sum, $total_remian);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum + $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['amount'], $result['deadline']);
                        info($text);
                        dump($text);
                        // $sms = new SmsService();
                        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                        $status = true;
                        if ($status) {
                            $customer->notifications()->create([
                                'customer_id' => $customer->id,
                                'payment_id' => $payment->id,
                                'customer_name' => $result['customer_name'],
                                'contract_no' => $payment->contract->contract_no,
                                'phone_number' => $result['customer_phone'],
                                'amount' => $result['amount'],
                                'amount_percent' => 0,
                                'status' => 0
                            ]);
                            $customer->save();
                        }
                    }
                }
            }
        }
        /*
            $customers = $region->customers;
            foreach ($customers as $customer) {
                $payments = $customer->notifyPayments;
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if (count($payments) > 0 and (count($customer->notifications) == 0 or $customer->notifications->last()->status == 1 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
                    $sum = 0;
                    $start = new Carbon('first day of this month');
                    $end = new Carbon('last day of this month');
                    foreach ($payments as $payment) {
                        if (Carbon::createFromDate($payment->deadline)->between($start, $end)) {
                            $sum += $payment->remain;
                        } else {
                            $delayInDays = Carbon::now()->diffInDays($payment->deadline);
                            if ($payment->percent == 0) {
                                $sum += $payment->remain;
                            } else {
                                $sum += (($payment->percent * $payment->remain) / 100) * $delayInDays + $payment->remain;
                            }
                        }
                    }
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!,\n Просим оплатить %sтг. в срок %s.";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['amount'], $result['deadline']);
                        info($text);
                        dump($text);
                        // $sms = new SmsService();
                        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = ' Union Partners LLP');
                        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
                        $status = true;
                        if ($status) {
                            $customer->notifications()->create([
                                'customer_id' => $customer->id,
                                'payment_id' => $payment->id,
                                'customer_name' => $result['customer_name'],
                                'contract_no' => $payment->contract->contract_no,
                                'phone_number' => $result['customer_phone'],
                                'amount' => $result['amount'],
                                'amount_percent' => 0,
                                'status' => 0
                            ]);
                            $customer->save();
                        }
                    }
                }
            }
            $result = [
                'msg' => 'Messages were sent to customers'
            ];

            return response()->json(
                $result,
                200
            );
        */
    }
}