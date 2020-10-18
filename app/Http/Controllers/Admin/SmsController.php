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
     * getData
     * return json customers(должники) and regions
     * @return Json
     */
    public function getData()
    {
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract', 'notifications')->where('deadline', '<', $now)->where('remain', '>', 0)->get();
        $payments = $payments->unique('customer_id');
        $regions = $customers = [];
        foreach ($payments as $payment) {
            if (count($payment->notifications) == 0 or Str::substr($payment->notifications->last()->created_at, 0, 10) == Str::substr(Carbon::now()->subMonth(), 0, 10)) {
                $customers[] = $payment->customer;
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
        $customer = [];
        foreach ($payments as $key => $payment) {
            if (count($payment->notifications) == 0) {
                $payment->setReaminAndPaid();
                $customer[] = [
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
            $customer,
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
            $customer_result = [
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
        $now = Carbon::now()->format('Y-m-d');
        $customers = $region->customers;
        $customer_result = [];
        foreach ($customers as $customer) {
            $item = Payment::with('contract')->where('customer_id', $customer->id)->where('deadline', '<', $now)->where('remain', '>', 0)->get()->unique('contract_id');
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
        $payments = Payment::with('manager', 'customer')->whereBetween('deadline', [$request->from, $request->to])->where('remain', '>', 0)->get()->unique('contract_id');
        if (count($payments) == 0) {
            $result = [
                'msg' => 'there are no unpaid customers in this region'
            ];
        } else {
            $result = [];
            foreach ($payments as $key => $payment) {
                if (count($payment->notifications) == 0) {
                    $payment->setReaminAndPaid();
                    $result[] = [
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
        }
        return response()->json(
            $result,
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
            if($request->customer_code != "null"){
                $status_list = explode(',', $request->customer_code);
                foreach($status_list as $status) {
                    if($status != "") {
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
        $payment = Payment::findOrFail($request->payment_id);
        $customer = $payment->customer;
        $message = "Unionp\nУважаемый %s!, Уведомляем вас, что ежемесячный платеж %sтг просрочен, сумма с процентом %s  дата %s.";
        $now = Carbon::now()->format('Y-m-d');
        $sum = 0;
        $contract_payments = Payment::where('contract_id', $payment->contract_id)->where('deadline', '<', $now)->where('sms_status', 'on')->where('remain', '>', 0)->get();

        foreach ($contract_payments as $payment_contract) {
            $delayInDays = Carbon::createFromDate($payment_contract->deadline)->addMonth()->diffInDays($payment_contract->deadline);
            if ($contract_payments->first()->percent == 0) {
                $sum += (Carbon::now()->month - Carbon::createFromDate($payment_contract->deadline)->month) * $payment_contract->remain;
            } else {
                $sum += (($payment_contract->percent * $payment_contract->remain) / 100) * $delayInDays + $payment_contract->remain;
            }
        }
        $result = [
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'amount' => $payment->amount,
            'deadline' => Str::substr($payment->deadline, 0, 10)
        ];
        $text = sprintf($message, $result['customer_name'], $result['amount'], $sum, $result['deadline']);
        // $sms = new SmsService();
        // list($sms_id) = $sms->send_sms($phones = $result['customer_phone'], $message = $text, $sender = 'UnionP');
        // list($status) = $sms->get_status($sms_id, $result['customer_phone']);
        info($text);
        $status = true;
        if ($status) {
            $payment->notifications()->create([
                'payment_id' => $payment->id,
                'customer_name' => $result['customer_name'],
                'phone_number' => $result['customer_phone'],
                'amount' => $result['amount'],
                'status' => 1
            ]);
            $payment->save();
        }
        dd($text);
    }
}