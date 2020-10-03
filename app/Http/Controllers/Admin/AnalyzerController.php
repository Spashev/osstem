<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\Region;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class AnalyzerController extends Controller
{
    public function index()
    {
        return view('analyzer.index');
    }

    public function getData()
    {
        $customers = Customer::get(['name', 'id']);
        $contracts = Contract::get(['contract_no', 'id']);
        $managers = Manager::get(['name', 'id']);
        $regions = Region::get(['name', 'id']);
        return response()->json(
            [
                'customers' => $customers,
                'contracts' => $contracts,
                'managers' => $managers,
                'regions' => $regions
            ],
            200
        );
    }

    public function getFilter(Request $request)
    {
        dd($request->toArray());
    }
}