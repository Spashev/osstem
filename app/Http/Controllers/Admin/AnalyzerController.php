<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\Region;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class AnalyzerController extends Controller
{
    public function index()
    {
        return view('analyzer.index');
    }

    public function upload()
    {
        return view('analyzer.upload');
    }

    public function getFilter(Request $request)
    {
        dd($request->toArray());
    }

    public function getData()
    {
        $customers = Customer::with('contracts')->get(['name', 'id']);
        $customers = $customers->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'name') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        $managers = Manager::get(['name', 'id']);
        $managers = $managers->map(function ($customer, $inex) {
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
                'managers' => $managers,
                'regions' => $regions
            ],
            200
        );
    }


    public function getContract(Customer $customer)
    {
        $contracts = $customer->contracts;
        $contracts = $contracts->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'contract_no') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        $contracts->push([
            'name' => $customer->name
        ]);
        return response()->json($contracts, 200);
    }

    public function getPayments(Contract $contract)
    {
        return response()->json($contract->payments->toArray());
    }

    public function getManager(Manager $manager)
    {
        $result = $manager->customers->map(function ($item, $index) {
            return [$item->name, $item->contracts->count()];
        });
        return response()->json($result);
    }

    public function getRegion(Region $region)
    {
        $result = $region->customers->map(function ($customer, $inex) {
            return collect($customer)->keyBy(function ($value, $key) {
                if ($key == 'name') {
                    return 'text';
                } else {
                    return $key;
                }
            });
        });
        return response()->json($result);
    }

    public function getDatePayments(Request $request)
    {
        $payments = Payment::with('manager', 'customer')->whereBetween('deadline', [$request->from, $request->to])->get();
        $groupBy = $payments->groupBy('deadline');
        return response()->json($groupBy->map(function ($item, $k) {
            return $item->count();
        })->toArray());
    }
}