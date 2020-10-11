<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $now = Carbon::now()->format('Y-m-d');
        // $payments = Payment::with('contract')->where('deadline', '<', $now)->where('remain', '>', 0)->get();
        // $payments = $payments->unique('contract_id');
        // $payments = new Paginator($payments, $perPage = 20);
        // $payments->setPath('/sms');
        // $last_page = $payments->toArray()['per_page'];
        // return view('sms.index', compact('payments', 'last_page'));
        return view('sms.index');
    }

    public function getData()
    {
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract')->where('deadline', '<', $now)->where('remain', '>', 0)->get();
        $payments = $payments->unique('customer_id');
        $regions = $customers = [];
        foreach ($payments as $payment) {
            $customers[] = $payment->customer;
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

    public function customer(Customer $customer)
    {
        $now = Carbon::now()->format('Y-m-d');
        $payments = Payment::with('contract')->where('customer_id', $customer->id)->where('deadline', '<=', $now)->where('remain', '>', 0)->get();
        $payments = $payments->unique('contract_id');
        $customer = [];
        foreach ($payments as $key => $payment) {
            $payment->setReaminAndPaid();
            $customer[] = [
                'id' => $key + 1,
                'contract_no' => $payment->contract->contract_no,
                'total_remain' => $payment->getTotalRemain(),
                'total_paid' => $payment->getTotalPaid(),
                'deadline' => Str::substr($payment->deadline, 0, 11)
            ];
        }
        return response()->json(
            $customer,
            200
        );
    }

    public function region(Region $region)
    {
        $now = Carbon::now()->format('Y-m-d');
        $customers = $region->customers;
        $payments = collect();
        foreach ($customers as $customer) {
            $item = Payment::with('contract')->where('customer_id', $customer->id)->where('deadline', '<=', $now)->where('remain', '>', 0)->get()->unique('contract_id');
            if (count($item) > 0) {
                $payments = $payments->merge($item);
            }
        }
        if (count($payments) == 0) {
            $result = [
                'msg' => 'there are no unpaid customers in this region'
            ];
        } else {
            $result = [];
            foreach ($payments as $key => $payment) {
                $payment->setReaminAndPaid();
                $result[] = [
                    'id' => $key + 1,
                    'contract_no' => $payment->contract->contract_no,
                    'total_remain' => $payment->getTotalRemain(),
                    'total_paid' => $payment->getTotalPaid(),
                    'deadline' => Str::substr($payment->deadline, 0, 11)
                ];
            }
        }
        return response()->json(
            $result,
            200
        );
    }

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
                $payment->setReaminAndPaid();
                $result[] = [
                    'id' => $key + 1,
                    'contract_no' => $payment->contract->contract_no,
                    'total_remain' => $payment->getTotalRemain(),
                    'total_paid' => $payment->getTotalPaid(),
                    'deadline' => Str::substr($payment->deadline, 0, 11)
                ];
            }
        }
        return response()->json(
            $result,
            200
        );
    }

    public function contractData(Request $request)
    {
        $contract = Contract::where('contract_no', $request->from)->first();
        $payments = Payment::where('contract_id', $contract->id)->where('remain', '>', 0)->get();
        $result = [];
        foreach ($payments as $key => $payment) {
            $result[] = [
                'id' => $key + 1,
                'contract_no' => $payment->contract->contract_no,
                'total_remain' => $payment->remain,
                'total_paid' => $payment->paid,
                'deadline' => Str::substr($payment->deadline, 0, 11)
            ];
        }
        return response()->json(
            $result,
            200
        );
    }
}