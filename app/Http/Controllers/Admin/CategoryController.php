<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('category', compact('categories'));
    }

    public function create() 
    {
        return view('admin.category');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->all());
        Session::flash('msg', 'Category created successfully!');
        return redirect()->back();
    }
}
