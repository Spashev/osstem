<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Smsc\SmsService;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sms.index');
    }

    /**
     * history
     *
     * @return void
     */
    public function history()
    {
        return view('sms.history');
    }

    /**
     * historyPaginations
     *
     * @param  mixed $request
     * @return void
     */
    public function historyPaginations(Request $request)
    {
        $pagination = Notification::orderBy('created_at', 'desc')->paginate(25);
        $customers = Customer::whereHas('notifications', function ($q) {
            return $q;
        })->get(['name', 'id']);
        $customers = $customers->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'name') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        $phones = Notification::get(['phone_number', 'id'])->unique('phone_number');
        $phones = $phones->map(function ($phone, $inex) {
            return collect($phone)->keyBy(function ($value, $key) {
                if ($key == 'phone_number') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        return response()->json(
            [
                'paginate' => $pagination,
                'customers' => $customers,
                'phones' => $phones,
            ],
            200
        );
    }

    /**
     * customerSms
     *
     * @param  mixed $request
     * @return void
     */
    public function customerSms(Customer $customer)
    {
        $customerSms = Notification::where('customer_name', $customer->name)->get();
        if (count($customerSms) == 0) {
            $customerSms = [
                'msg' => 'User did not receive SMS notification'
            ];
        }
        return response()->json(
            [
                'paginate' => $customerSms
            ],
            200
        );
    }

    public function phoneSms(Notification $notification)
    {
        $phone_numbers = Notification::where('phone_number', $notification->phone_number)->get();
        return response()->json(
            [
                'paginate' => $phone_numbers
            ],
            200
        );
    }

    /**
     * getData
     * return json customers(должники) and regions
     * @return Json
     */
    public function getData()
    {
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract', 'notifications')->where('deadline', '<', $now)->where('remain', '>', 0)->get();
        $payments = $payments->unique('customer_id');
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        $regions = $customers = [];
        foreach ($payments as $payment) {
            $customer = $payment->customer;
            if (count($customer->notifications) == 0 or  $customer->notifications->last()->status == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
                # payment поменял на customer
                $customers[] = $customer;
            }
        }
        $customers = collect($customers)->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'name') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
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
                'customers' => $customers,
                'regions' => $regions
            ],
            200
        );
    }

    /**
     * customer where remain > 0(должники)
     * 
     * @param  mixed $customer
     * @return Json
     */
    public function customer(Customer $customer)
    {
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract')->where('customer_id', $customer->id)->where('deadline', '<=', $now)->where('remain', '>', 0)->get();
        $payments = $payments->unique('contract_id');
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        $customer_result = [];
        foreach ($payments as $key => $payment) {
            if (count($customer->notifications) == 0 or  $customer->notifications->last()->status == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
                $payment->setReaminAndPaid();
                $customer_result[] = [
                    'payment_id' => $payment->id,
                    'id' => $key + 1,
                    'contract_no' => $payment->contract->contract_no,
                    'total_remain' => $payment->getTotalRemain(),
                    'total_paid' => $payment->getTotalPaid(),
                    'deadline' => Str::substr($payment->deadline, 0, 11),
                    'sms_status' => $payment->sms_status
                ];
            }
        }
        return response()->json(
            $customer_result,
            200
        );
    }

    public function customerData(Request $request)
    {
        $contract = Contract::where('contract_no', $request->contract_no)->first();
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::where('contract_id', $contract->id)->where('remain', '>', 0)->where('deadline', '<', $now)->get();
        $result = [];
        foreach ($payments as $key => $payment) {
            $customer = $payment->customer;
            if (count($customer->notifications) == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
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
     * return customers from region and payments(должники)
     *
     * @param  mixed $region
     * @return Json
     */
    public function region(Region $region)
    {
        $customers = $region->customers;
        $customer_result = [];
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');
        foreach ($customers as $customer) {
            if ($customer->sms_status == 'on' and $customer->notifications->count() == 0) {
                $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->where('deadline', '<', Carbon::now()->format('Y-m-d'))->where('sms_status', 'on')->get();
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if (count($payments) > 0 and $payments->last() !== null) {
                    if ($customer->notifications->count() == 0) {
                        $customer_result[] = [
                            'text' => $customer->name,
                            'id' => $customer->id,
                            'customer_code' => $customer->customer_id,
                            /* 'amount' => $payments->last()->amount, */
                            'amount' => $payments->sum('amount'),
                            'total_remain' => $payments->last()->getRemain(),
                            'total_paid' => $payments->last()->getCustomerPaid(),
                            'sms_status' => $customer->sms_status
                        ];
                    } else if ($customer->notifications->count() > 0) {
                        $notify_flag = true;
                        foreach ($customer->notifications as $notify) {
                            if ($notify->created_at->between($start, $end) and $notify->status == 1) {
                                $notify_flag = false;
                                break;
                            }
                        }
                        if ($notify_flag) {
                            $customer_result[] = [
                                'text' => $customer->name,
                                'id' => $customer->id,
                                'customer_code' => $customer->customer_id,
                               /*  'amount' => $payments->last()->amount, */
                               'amount' => $payments->sum('amount'),
                                'total_remain' => $payments->last()->getCustomerRemain(),
                                'total_paid' => $payments->last()->getCustomerPaid(),
                                'sms_status' => $customer->sms_status
                            ];
                        }
                    }
                }
            } else {
                $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->where('deadline', '<', Carbon::now()->format('Y-m-d'))->where('sms_status', 'on')->get();
                $payments = $payments->filter(function ($value, $key) {
                    return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                });
                if (count($payments) > 0 and $payments->last() !== null) {
                    if ($customer->notifications->count() == 0) {
                        $customer_result[] = [
                            'text' => $customer->name,
                            'id' => $customer->id,
                            'customer_code' => $customer->customer_id,
                            /* 'amount' => $payments->last()->amount, */
                            'amount' => $payments->sum('amount'),
                            'total_remain' => $payments->last()->getCustomerRemain(),
                            'total_paid' => $payments->last()->getCustomerPaid(),
                            'sms_status' => $customer->sms_status
                        ];
                    } else if ($customer->notifications->count() > 0) {
                        $notify_flag = true;
                        foreach ($customer->notifications as $notify) {
                            if ($notify->created_at->between($start, $end) and $notify->status == 1) {
                                $notify_flag = false;
                                break;
                            }
                        }
                        if ($notify_flag) {
                            $customer_result[] = [
                                'text' => $customer->name,
                                'id' => $customer->id,
                                'customer_code' => $customer->customer_id,
                                /* 'amount' => $payments->last()->amount, */
                                'amount' => $payments->sum('amount'),
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
     * data
     *
     * @param  mixed $request
     * @return Json
     */
    public function data(Request $request)
    {
        $payments = Payment::with('customer')->whereBetween('deadline', [$request->from, $request->to])->where('remain', '>', 0)->get()->unique('customer_id');
        $customer_result = [];
        if (count($payments) == 0) {
            $customer_result = [
                'msg' => 'there are no unpaid customers in this region'
            ];
        } else {
            foreach ($payments as $payment) {
                $customer = $payment->customer;
                if ($customer->sms_status == 'on') {
                    $payments = Payment::where('customer_id', $customer->id)->where('sms_status', 'on')->where('remain', '>', 0)->where('deadline', '<', Carbon::now()->format('Y-m-d'))->get()->unique('customer_id');
                    if (count($payments) > 0 && ($customer->notifications->count() == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
                        foreach ($payments as $payment) {
                            $customer_result[] = [
                                'text' => $customer->name,
                                'id' => $customer->id,
                                'customer_code' => $customer->customer_id,
                                'amount' => $payment->amount,
                                'total_remain' => $payment->getCustomerRemain(),
                                'total_paid' => $payment->getCustomerPaid(),
                                'sms_status' => $customer->sms_status
                            ];
                        }
                    }
                }
            }
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
     * @return Json
     */
    public function contractData(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');
        $customer = Customer::where('customer_id', $request->customer_id)->first();
        $payments = Payment::where('customer_id', $customer->id)->where('remain', '>', 0)->where('deadline', '<', $now)->get();
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        $result = [];
        foreach ($payments as $key => $payment) {
            $result[] = [
                'payment_id' => $payment->id,
                'id' => $key + 1,
                'contract_no' => $payment->contract->contract_no,
                'amount' => $payment->amount,
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
     * changed sms_status from payments
     * @param  mixed $request
     * @return Json
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
     * sendSms уведомления о просрочке клиента
     *
     * @param  mixed $request
     * @return void
     */
    public function sendSms(Request $request)
    {
        $contract = Contract::where('contract_no', $request->contract_no)->first();
        $customer = $contract->customer;
        $now = Carbon::now()->format('Y-m-d');
        $payments = $customer->deadlinePayments;
        $payments = $payments->filter(function ($value, $key) {
            return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
        });
        if ($payments->count() > 0 and ($customer->notifications->count() == 0 or $customer->notifications->last()->status == 0 or Str::substr($customer->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10))) {
            dump('Пеня');
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
                // dump($sum, $delayInDays, $payment->remain, $payment->deadline);
            }
            if ($sum != 0) {
                $message = "Уважаемый Клиент!\nЗадолжность на %s\nДолг: %s\nПеня: %s";
                $result = [
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'amount' => $sum,
                    'total_remain' => $total_remian,
                    'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                ];
                $text = sprintf($message, $now, $result['total_remain'], $result['amount']);
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
                        'contract_no' => $request->contract_no,
                        'phone_number' => $result['customer_phone'],
                        'amount' => $result['total_remain'],
                        'amount_percent' => $result['amount'],
                        'status' => 1
                    ]);
                    $customer->save();
                }
            }
        }
    }

    /**
     * sendRegionSms уведомления о просрочке по Региону
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
                    dump($sum, $total_remian);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!\nЗадолжность на %s\nДолг: %s\nПеня: %s";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum,
                            'total_remain' => $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['deadline'], $result['total_remain'], $result['amount']);
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
                                'amount' => $result['total_remain'],
                                'amount_percent' => $result['amount'],
                                'status' => 1
                            ]);
                            $customer->save();
                        }
                    }
                }
            } else if ($customer->sms_status == 'on' and $customer->notifications->count() > 0) {
                $notify_flag = true;
                foreach ($customer->notifications as $notify) {
                    if ($notify->created_at->between($start, $end) and $notify->status == 1) {
                        $notify_flag = false;
                        break;
                    }
                }
                if ($notify_flag) {
                    $sum = 0;
                    $total_remian = 0;
                    $payments = $customer->deadlinePayments;
                    $payments = $payments->filter(function ($value, $key) {
                        return $value->remain > 0 and $value->sms_status == 'on' and substr($value->contract->contract_no, 0, 2) !== 'TO' and substr($value->contract->contract_no, 0, 4) !==  'ITEM';
                    });
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
                    dump($sum, $total_remian);
                    if ($sum != 0) {
                        $message = "Уважаемый Клиент!\nЗадолжность на %s\nДолг: %s\nПеня: %s";
                        $result = [
                            'customer_name' => $customer->name,
                            'customer_phone' => $customer->phone,
                            'amount' => $sum,
                            'total_remain' => $total_remian,
                            'deadline' => Str::substr(Carbon::now()->format('Y-m-d'), 0, 10),
                        ];
                        $text = sprintf($message, $result['deadline'], $result['total_remain'], $result['amount']);
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
                                'contract_no' => $sum,
                                'phone_number' => $result['customer_phone'],
                                'amount' => $result['total_remain'],
                                'amount_percent' => $result['amount'],
                                'status' => 1
                            ]);
                            $customer->save();
                        }
                    }
                }
            }
        }
    }
}