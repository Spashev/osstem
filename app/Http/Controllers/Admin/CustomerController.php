<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

class CustomerController extends Controller
{
    public function get_csv($id)
    {
        $customer = Customer::with('payments', 'manager')->find($id);
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
        foreach ($customer->payments as $payment) {
            $row['CUSTOMER']  = $customer->customer_id;
            $row['REGION ID']    = $customer->region_id;
            $row['REGION']    = $customer->region;
            $row['IN-CHARGE']  = $customer->manager->in_charge;
            $row['MANAGER']  = $customer->manager->name;
            $row['CONTRACT NO']  = $payment->contract_no;
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
        fclose($file);
        return response()->download('storage/upload/customer.csv');
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $customer = Customer::with('manager', 'payments')->findOrFail($id);
        // dd($customer->payments);
        return view('customer.show', compact('customer'));
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}