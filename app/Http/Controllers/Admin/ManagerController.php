<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ManagerRequest;

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

    public function update(ManagerRequest $request, Manager $manager)
    {
        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->in_charge = $request->in_charge;
        $manager->save();
        Session::flush('msg','Data updated successfully.');
        return redirect()->back();
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