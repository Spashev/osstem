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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ExcelFilterRequest;
use App\Jobs\ImportJob;

class ExcelController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return view('excel.index');
    }

    /**
     * upload
     *
     * @param  mixed $request
     * @return void
     */
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
        if ($validator) {;
            $title = $request->file('file')->getClientOriginalName();
            if ($request->hasFile('file')) {
                $file = $request->file('file')->store('upload', 'public');
            }
            $excel = Excel::updateOrCreate(
                ['title' => $title],
                ['path' => $file]
            );
            $result = ExcelJob::dispatch($excel);
            if (isset($result)) {
                dump($result);
            }
        }
    }

    /**
     * download
     *
     * @return void
     */
    public function download()
    {
        $filename = '/update_payment.csv';
        $path = public_path('storage/upload' . $filename);
        if (file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        } else {
            return redirect()->back();
        }
    }

    /**
     * payment
     *
     * @return void
     */
    public function payment()
    {
        $payments =  Payment::all();
        return view('payment.index', compact('payments'));
    }

    /**
     * return table of payments
     *
     * @param  mixed $request
     * @return void
     */
    public function table(Request $request)
    {

        if ($request->has('search_input')) {
            $payments = Payment::with('contract')
                ->where('remain', $request->search_input)
                ->orWhere('paid', $request->search_input)
                ->orWhere('amount', $request->search_input)
                ->get();
            if (count($payments) > 0) {
                return view('excel.table', compact('payments'));
            }

            $customers = Customer::with('contracts', 'manager')
                ->where('name', 'LIKE', '%' . $request->search_input . '%')
                ->orWhere('region', $request->search_input)
                ->get();
            if (count($customers) > 0) {
                $results = [];
                foreach ($customers as $customer) {
                    foreach ($customer->contracts as $contract) {
                        $results[] = [
                            'name' => $customer->name,
                            'region' => $customer->region,
                            'payments' => $contract->payments,
                            'manager_name' => $contract->manager->name,
                            'in_charge' => $contract->manager->in_charge,

                        ];
                    }
                }
                return view('excel.customers', compact('results'));
            }

            $managers = Manager::with('contracts', 'customers')
                ->where('name', 'LIKE', '%' . $request->search_input . '%')
                ->orWhere('in_charge', 'LIKE', '%' . $request->search_input . '%')
                ->get();
            if (count($managers) > 0) {
                return $managers;
            }

            $contracts = Contract::with('payments', 'manager', 'customer')
                ->where('contract_no', 'LIKE', '%' . $request->search_input . '%')
                ->get();
            if (count($contracts) > 0) {
                return view('payment.contract', compact('contracts'));
            }
        }

        if ($request->sort == 'contract_no') {
            $contracts = Contract::with('payments', 'manager', 'customer')
                ->orderBy('contract_no', $request->direction)
                ->paginate(20);
            return view('payment.contract', compact('contracts'));
        }

        $payments = Payment::with('contract')->sortable()->paginate(20);
        $managers = Manager::all();
        $customers = Customer::all();
        $contracts = Contract::all();

        return view('excel.table', compact('payments', 'managers', 'customers', 'contracts'));
    }

    /**
     * filter don't work(fix it)
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        if (!is_null($request->manager)) {
            $manager = Manager::where('name', 'LIKE', '%' . $request->manager . '%')->get();
            dd($manager->toArray());
        } else if (!is_null($request->customer)) {
            $customer = Customer::where('name', 'LIKE', '%' . $request->customer . '%')->get();
            dd($customer->toArray());
        } else if (!is_null($request->customer_no)) {
            $contract = Contract::where('contract_no', 'LIKE', '%' . $request->contract_no . '%')->get();
            dd($contract->toArray());
        } else {
            $deadline = Payment::where('deadline', 'LIKE', '%' . $request->deadline . '%')->get();
            dd($deadline->toArray());
        }
    }

    /**
     * Payment's filters
     *
     * @param  mixed $request
     * @return void
     */
    public function filters(ExcelFilterRequest $request)
    {
        $managers = Manager::all();
        $customers = Customer::all();
        $contracts = Contract::all();
        if ($request->manager != 0) {
            $manager = Manager::findorFail($request->manager);
            $payments_result = [];
            if ($manager and !is_null($request->deadline)) {
                foreach ($manager->payments as $payment) {
                    if (Str::substr($payment->deadline, 0, 10) == $request->deadline) {
                        $customer = $payment->customer;
                        $payments_result[] = [
                            'id' => $payment->id,
                            'in_charge' => $manager->in_charge,
                            'manager' => $manager->name,
                            'region' => $customer->region,
                            'cutomer_name' => $customer->name,
                            'contract_no' => $payment->contract->contract_no,
                            'amount' => $payment->amount,
                            'seq' => $payment->seq,
                            'deadline' => $payment->deadline,
                            'paid' => $payment->paid,
                            'remain' => $payment->remain
                        ];
                    }
                }
            } else {
                foreach ($manager->payments as $payment) {
                    $customer = $payment->customer;
                    $payments_result[] = [
                        'id' => $payment->id,
                        'in_charge' => $manager->in_charge,
                        'manager' => $manager->name,
                        'region' => $customer->region,
                        'cutomer_name' => $customer->name,
                        'contract_no' => $payment->contract->contract_no,
                        'amount' => $payment->amount,
                        'seq' => $payment->seq,
                        'deadline' => $payment->deadline,
                        'paid' => $payment->paid,
                        'remain' => $payment->remain
                    ];
                }
            }
            return view('excel.table_filter', compact('payments_result', 'managers', 'customers', 'contracts'));
        }
        if ($request->customer != 0) {
            $customer = Customer::findorFail($request->customer);
            $payments_result = [];
            $manager = $customer->manager;
            if (!is_null($request->deadline)) {
                foreach ($customer->payments as $payment) {
                    if (Str::substr($payment->deadline, 0, 10) == $request->deadline) {
                        $customer = $payment->customer;
                        $payments_result[] = [
                            'id' => $payment->id,
                            'in_charge' => $manager->in_charge,
                            'manager' => $manager->name,
                            'region' => $customer->region,
                            'cutomer_name' => $customer->name,
                            'contract_no' => $payment->contract->contract_no,
                            'amount' => $payment->amount,
                            'seq' => $payment->seq,
                            'deadline' => $payment->deadline,
                            'paid' => $payment->paid,
                            'remain' => $payment->remain
                        ];
                    }
                }
            } else {
                foreach ($customer->payments as $payment) {
                    $payments_result[] = [
                        'id' => $payment->id,
                        'in_charge' => $manager->in_charge,
                        'manager' => $manager->name,
                        'region' => $customer->region,
                        'cutomer_name' => $customer->name,
                        'contract_no' => $payment->contract->contract_no,
                        'amount' => $payment->amount,
                        'seq' => $payment->seq,
                        'deadline' => $payment->deadline,
                        'paid' => $payment->paid,
                        'remain' => $payment->remain
                    ];
                }
            }
            return view('excel.table_filter', compact('payments_result', 'managers', 'customers', 'contracts'));
        }
        if ($request->contract_no != 0) {
            $contract = Contract::findOrFail($request->contract_no);
            $payments_result = [];
            $manager = $contract->manager;
            if (!is_null($request->deadline)) {
                foreach ($contract->payments as $payment) {
                    if (Str::substr($payment->deadline, 0, 10) == $request->deadline) {
                        $customer = $payment->customer;
                        $payments_result[] = [
                            'id' => $payment->id,
                            'in_charge' => $manager->in_charge,
                            'manager' => $manager->name,
                            'region' => $customer->region,
                            'cutomer_name' => $customer->name,
                            'contract_no' => $payment->contract->contract_no,
                            'amount' => $payment->amount,
                            'seq' => $payment->seq,
                            'deadline' => $payment->deadline,
                            'paid' => $payment->paid,
                            'remain' => $payment->remain
                        ];
                    }
                }
            } else {
                foreach ($contract->payments as $payment) {
                    $customer = $payment->customer;
                    $payments_result[] = [
                        'id' => $payment->id,
                        'in_charge' => $manager->in_charge,
                        'manager' => $manager->name,
                        'region' => $customer->region,
                        'cutomer_name' => $customer->name,
                        'contract_no' => $payment->contract->contract_no,
                        'amount' => $payment->amount,
                        'seq' => $payment->seq,
                        'deadline' => $payment->deadline,
                        'paid' => $payment->paid,
                        'remain' => $payment->remain
                    ];
                }
            }
            return view('excel.table_filter', compact('payments_result', 'managers', 'customers', 'contracts'));
        }
        if (!is_null($request->deadline)) {
            $payments = Payment::where('deadline', $request->deadline)->get();
            $payments_result = [];
            foreach ($payments as $payment) {
                $customer = $payment->customer;
                $manager = $payment->manager;
                $payments_result[] = [
                    'id' => $payment->id,
                    'in_charge' => $manager->in_charge,
                    'manager' => $manager->name,
                    'region' => $customer->region,
                    'cutomer_name' => $customer->name,
                    'contract_no' => $payment->contract->contract_no,
                    'amount' => $payment->amount,
                    'seq' => $payment->seq,
                    'deadline' => $payment->deadline,
                    'paid' => $payment->paid,
                    'remain' => $payment->remain
                ];
            }
            return view('excel.table_filter', compact('payments_result', 'managers', 'customers', 'contracts'));
        }
    }

    /**
     * return create view
     *
     * @return void
     */
    public function create()
    {
        $managers = Manager::all();
        $customers = Customer::all();
        return view('excel.create', compact('managers', 'customers'));
    }

    /**
     * save new Contract and Payments
     *
     * @param  mixed $request
     * @return void
     */
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
                'manager_id' => $request->manager,
                'customer_id' => $request->customer,
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

    /**
     * edit view
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $now = Str::substr(Carbon::now(), 0, 10);
        $subMonth = Carbon::now()->subMonth()->format('Y-m-d');
        $payment = Payment::with('contract')->findOrFail($id);
        $managers = Manager::all();
        $customers = Customer::all();
        if ($payment->paid == 0 and $payment->remain != 0) {
            $minusDays = intval(Str::substr($now, 8, 10)) - intval(Str::substr($payment->payment_date, 8, 10));
            $amount_percent = ((($payment->percent * $payment->amount) / 100) * $minusDays) + $payment->amount;
        } else {
            $amount_percent = 0;
        }
        return view('excel.edit', compact('payment', 'customers', 'managers', 'amount_percent'));
    }

    /**
     * updated customer payment
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->contract->manager_id = $request->manager;
        $customer = Customer::findOrFail($request->customer_name);
        if ($request->contract_no) {
            $payment->contract->contract_no = $request->contract_no;
        }
        $payment->amount = $request->amount;
        $payment->paid = $request->paid;
        $payment->percent = $request->percent;
        $payment->remain = $payment->amount - $request->paid;
        $payment->payment_date = Carbon::parse($request->payment_day)->format('Y-m-d');
        $payment->deadline = Carbon::parse($request->deadline)->format('Y-m-d');
        $payment->customer_id = $customer->id;
        $payment->save();
        $payment->contract->save();

        Session::flash('msg', 'Data updated');
        return redirect()->route('admin.excel.table');
    }

    /**
     * delete
     *
     * @param  mixed $payment
     * @return void
     */
    public function delete(Payment $payment)
    {
        $payment->delete();
        return redirect()->back();
    }

    /**
     * import customer address, email, phone ...
     *
     * @return void
     */
    public function import(Request $request)
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
            $result = ImportJob::dispatch($excel);
            if (isset($result)) {
                dump($result);
            }
        }
    }
}