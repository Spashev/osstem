<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function customer(Request $request)
    {
        $managers = Manager::all();
        if (request()->has('search_input')) {
            $customers = Customer::where('name', 'LIKE', '%' . $request->search_input . '%')
                ->orWhere('customer_id', 'LIKE', '%' . $request->search_input . '%')
                ->get();
        }
        if (!isset($customers)) {
            $customers = Customer::paginate(20);
        }

        return view('customer.index', compact('customers', 'managers'));
    }

    public function get_csv($id)
    {
        $customer = Customer::with('contracts', 'manager')->find($id);
        $fileName = '/customer.csv';
        $columns = [
            'CUSTOMER',
            'CUSTOMER NAME',
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
                $row['CUSTOMER NAME']  = $customer->name;
                $row['CUSTOMER PHONE']  = $customer->phone;
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
                    $row['CUSTOMER NAME'],
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


    public function store(CustomerRequest $request)
    {
        Customer::create([
            'customer_id' => $request->customer_id,
            'manager_id' => $request->manager_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'region' => $request->region,
            'region_id' => $request->region_id,
            'sms_status' => is_null($request->sms_status) ? 'off' : 'on'
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

    public function edit($id)
    {
        $customer = Customer::with('manager', 'contracts')->findOrFail($id);
        $managers = Manager::all();
        return view('customer.edit', compact('customer', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->toArray());
        $customer->sms_status = is_null($request->sms_status) ? 'off' : 'on';
        $customer->save();
        Session::flash('msg', 'Customer, ' . $customer->name . ' updated.');
        return redirect()->back();
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.excel.customers');
    }
}