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
        foreach($payments as $payment) {
            $customer = $payment->customer;
            $result[] = [
                // 'payment_id' => $payment->id,
                'text' => $customer->name,
                'id' => $customer->customer_id,
                // 'amount' => $payment->amount,
                // 'total_paid' => $payment->getPaid(),
                // 'total_remain' => $payment->getRemain()
            ];
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
            $item = Payment::with('contract')->where('customer_id', $customer->id)->whereBetween('deadline', [$start, $end])->where('remain', '>', 0)->get();
            if (count($item) > 0 && count($item->last()->notifications) == 0) {
                $customer_result[] = [
                    'text' => $customer->name,
                    'id' => $customer->id,
                    'customer_code' => $customer->customer_id,
                    'total_remain' => $item->first()->getRemain(),
                    'total_paid' => $item->first()->getPaid(),
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
}
