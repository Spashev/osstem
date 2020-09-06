<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManagerController extends Controller
{
    public function manager()
    {
        $managers = Manager::all();
        return view('manager.index', compact('managers'));
    }

    public function show($id)
    {
        $manager = Manager::with('customers')->findOrFail($id);
        return view('manager.show', compact('manager'));
    }

    public function managerEdit(Request $request, Manager $manager)
    {
        dd($manager);
    }

    public function managerDelete(Manager $manager)
    {
        $manager->delete();
        return redirect()->back();
    }

    public function save(Request $request)
    {
        Manager::create($request->toArray());
        Session::flash('msg', 'Manager saved');
        return redirect()->back();
    }
}