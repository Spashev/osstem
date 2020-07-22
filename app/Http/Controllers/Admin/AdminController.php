<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $products = Product::all();
        $categories = Category::all();
        return view('admin.index', compact('users', 'products', 'categories'));
    }
    public function orders() 
    {
        $orders = Order::all();
        $products = [];
        foreach($orders as $order) {
            dump($order->product);
        }
        dd('hz');
        return view('admin.order', compact('orders'));
    }
}
