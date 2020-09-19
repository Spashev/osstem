<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Jobs\ExcelJob;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Excel;
use App\Models\Manager;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


use League\Csv\Reader;

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
            $result = ExcelJob::dispatch($excel);
        }
    }
    public function download()
    {
        $filename = '/update_payment.csv';
        $path = public_path('storage/upload' . $filename);
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function payment()
    {
        $payments =  Payment::all();
        return view('payment.index', compact('payments'));
    }

    public function table()
    {
        $payments = Payment::with('contract')->paginate(20);
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

        $contract = Contract::firstOrCreate([
            'customer_id' => $request->customer,
            'manager_id' => $request->manager,
            'contract_no' => $request->contract_no,
        ]);
        for ($i = 1; $i < $request->seq + 1; $i++) {
            Payment::create([
                'hash' => '$_' . $request->contract_no . '_S_' . $i,
                'contract_id' => $contract->id,
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
        $now = Str::substr(Carbon::now(), 0, 10);
        $subMonth = Carbon::now()->subMonth()->format('Y-m-d');
        $payment = Payment::with('contract')->findOrFail($id);
        $managers = Manager::all();
        $customers = Customer::all();
        if($payment->paid == 0 and $payment->remain != 0) {
            $minusDays = intval(Str::substr($now, 8, 10)) - intval(Str::substr($payment->payment_date, 8, 10));
            $amount_percent = ((($payment->percent * $payment->amount) / 100) * $minusDays) + $payment->amount;
        } else {
            $amount_percent = 0;
        }
        return view('excel.edit', compact('payment', 'customers', 'managers', 'amount_percent'));
    }

    public function update(Request $request, $id)
    {

        $payment = Payment::findOrFail($id);
        $payment->contract->manager_id = $request->manager;
        if ($request->contract_no) {
            $payment->contract->contract_no = $request->contract_no;
        }
        $payment->amount = $request->amount;
        $payment->paid = $request->paid;
        $payment->percent = $request->percent;
        $payment->remain = $request->paid != 0 ? $payment->remain - $request->paid : $payment->amount;
        $payment->payment_date = Carbon::parse($request->payment_day)->format('Y-m-d');
        $payment->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $payment->save();
        $payment->contract->save();

        Session::flash('msg', 'Data updated');
        return redirect()->back();
    }

    public function delete(Payment $payment)
    {
        $payment->delete();
        return redirect()->back();
    }

    public function import()
    {
        $inputFileName = public_path('client_address.csv');
        $reader = Reader::createFromPath($inputFileName, 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        foreach($records as $record) {
            $nomer = $record['CELL'] ? $record['CELL'] : $record['TEL NO'];
            $customer = Customer::where('customer_id', $record['CODE'])->first();
            if($customer) {
                $customer->phone = $nomer;
                $customer->city = $record['(Bill To)'];
                $customer->district = $record['__EMPTY_1'];
                $customer->address = $record['address'];
                $customer->save();
            }
        }
        return 'All customer contacts saved successfully';
    }
}