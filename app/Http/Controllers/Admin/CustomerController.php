<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function customer()
    {
        $customers = Cache::remember('customers', Carbon::now()->addMinutes(1), function () {
            return Customer::all();
        });
        $managers = Cache::remember('managers', Carbon::now()->addMinutes(1), function () {
            return Manager::all();
        });

        return view('customer.index', compact('customers', 'managers'));
    }

    public function get_csv($id)
    {
        $customer = Customer::with('contracts', 'manager')->find($id);
        $fileName = '/customer.csv';
        $columns = [
            'CUSTOMER',
            'REGION ID',
            'REGION',
            'IN-CHARGE',
            'MANAGER',
            'CONTRACT NO',
            'SEQ',
            'DEADLINE',
            'AMOUNT',
            'PAID',
            'REMAIN',
            'PERCENT',
            'AMOUNT PERCENT'
        ];
        $path = public_path('storage/upload' . $fileName);
        $file = fopen($path, 'w+');
        fputcsv($file, $columns);
        foreach ($customer->contracts as $contract) {
            foreach ($contract->payments as $payment) {
                $row['CUSTOMER']  = $customer->customer_id;
                $row['REGION ID']    = $customer->region_id;
                $row['REGION']    = $customer->region;
                $row['IN-CHARGE']  = $customer->manager->in_charge;
                $row['MANAGER']  = $customer->manager->name;
                $row['CONTRACT NO']  = $contract->contract_no;
                $row['SEQ']  = $payment->seq;
                $row['DEADLINE']  = $payment->deadline;
                $row['AMOUNT']  = $payment->amount;
                $row['PAID']  = $payment->paid;
                $row['REMAIN']  = $payment->remain;
                $row['PERCENT']  = $payment->percent;
                $row['AMOUNT PERCENT']  = $payment->amount_percent;
                fputcsv($file, [
                    $row['CUSTOMER'],
                    $row['REGION ID'],
                    $row['REGION'],
                    $row['IN-CHARGE'],
                    $row['MANAGER'],
                    $row['CONTRACT NO'],
                    $row['SEQ'],
                    $row['DEADLINE'],
                    $row['AMOUNT'],
                    $row['PAID'],
                    $row['REMAIN'],
                    $row['PERCENT'],
                    $row['AMOUNT PERCENT']
                ]);
            }
        }
        fclose($file);
        return response()->download('storage/upload/customer.csv');
    }

    public function invoice($id)
    {
        $customer = Customer::with('contracts')->findOrFail($id);
        $total = 0;
        $remain_total = 0;
        foreach ($customer->contracts as $contract) {
            foreach ($contract->payments as $payment) {
                $total += $payment->paid;
                $remain_total += $payment->remain;
            }
        }
        return view('customer.invoice', compact('customer', 'total', 'remain_total'));
    }


    public function store(Request $request)
    {
        Customer::create([
            'customer_id' => $request->customer_id,
            'manager_id' => $request->manager_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'region' => $request->region,
            'region_id' => $request->region_id
        ]);

        Session::flash('msg', 'customer created');
        return redirect()->back();
    }


    public function show($id)
    {
        $customer = Customer::with('manager', 'contracts')->findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    public function destroy(Manager $manager)
    {
        $manager->delete();
        return redirect()->back();
    }
}