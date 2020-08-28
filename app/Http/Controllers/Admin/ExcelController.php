<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExcelJob;
use App\Models\Excel;
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
                'extension'      => 'required|in:csv,xlsx,xls',
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
}
