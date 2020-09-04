<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Jobs\ExcelJob;
use App\Models\Customer;
use App\Models\Excel;
use App\Models\Manager;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExcelController extends Controller
{
    public function index()
    {
        return view('excel.index');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make(
            [
                'file'      => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:csv',
            ]
        );
        if ($validator) {
            $title = $request->file('file')->getClientOriginalName();
            if ($request->hasFile('file')) {
                $file = $request->file('file')->store('upload', 'public');
            }
            $excel = Excel::updateOrCreate(
                ['title' => $title],
                ['path' => $file]
            );
            ExcelJob::dispatch($excel);
        }

        // Session::flash('msg', 'Excel '.$excel->title.' file saved success');
        // return redirect()->back();
    }

    public function customer()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    public function manager()
    {
        $managers = Manager::paginate(10);
        return view('manager.index', compact('managers'));
    }

    public function managerEdit(Request $request, Manager $manager)
    {
        dd($manager);
    }

    public function managerDelete(Request $request, Manager $manager)
    {
        dd($manager);
    }

    public function payment()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }

    public function table()
    {
        $payments = Payment::with('manager', 'customer')->paginate(20);
        return view('excel.table', compact('payments'));
    }

    public function create()
    {
        $managers = Manager::all();
        $customers = Customer::all();
        return view('excel.create', compact('managers', 'customers'));
    }

    public function save(PaymentRequest $request)
    {
        for ($i = 1; $i < $request->seq + 1; $i++) {
            Payment::create([
                'customer_id' => $request->customer,
                'manager_id' => $request->manager,
                'contract_no' => $request->contract_no,
                'seq' => $i,
                'amount' => $request->amount,
                'payment_date' => $i == 0 ? Carbon::parse($request->payment_day)->format('Y-m-d') : Carbon::parse($request->payment_day)->addMonth($i)->format('Y-m-d'),
                'deadline' => $i == 0 ? Carbon::parse($request->deadline)->format('Y-m-d') : Carbon::parse($request->deadline)->addMonth($i)->format('Y-m-d'),
                'paid' => 0,
                'remain' => $request->amount
            ]);
        }
        Session::flash('msg', 'All data saved');
        return redirect()->back();
    }

    public function edit($id)
    {
        $payment = Payment::with('customer', 'manager')->find($id);
        $managers = Manager::all();
        $customers = Customer::all();
        return view('excel.edit', compact('payment', 'customers', 'managers'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $payment = Payment::find($id);
        $payment->manager_id = $request->manager;
        $payment->contract_no = $request->contract_no;
        $payment->amount = $request->amount;
        $payment->paid = $request->paid;
        $payment->remain = $request->paid != 0 ? $payment->remain - $request->paid : $payment->amount;
        $payment->payment_date = Carbon::parse($request->payment_day)->format('Y-m-d');
        $payment->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $payment->save();

        Session::flash('msg', 'Data updated');
        return redirect()->back();
    }

    public function delete(Payment $payment)
    {
        dd($payment);
    }
}