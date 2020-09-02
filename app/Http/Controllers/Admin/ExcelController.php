<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExcelJob;
use App\Models\Customer;
use App\Models\Excel;
use App\Models\Manager;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
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
        if($validator) {
            $title = $request->file('file')->getClientOriginalName();
            if($request->hasFile('file')) {
                $file = $request->file('file')->store('upload','public');
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

    public function managerEdit(Request $request, Manager $manager) {
        dd($manager);
    }

    public function managerDelete(Request $request, Manager $manager) {
        dd($manager);
    }

    public function payment()
    {
        $payments = Payment::all();
        return view('payment.index', compact('payments'));
    }

    public function table()
    {
        $payments = Payment::with('manager','customer')->paginate(20);
        return view('excel.table', compact('payments'));
    }
}
