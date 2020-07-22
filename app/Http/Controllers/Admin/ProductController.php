<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function getCategoryProduct($code)
    {
        $products = Category::where('code', $code)->all();
        dd($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'currency' => $request->currency,
            'is_published' => $request->is_published ?? 'off',
            'code' => $request->code
        ]);
        foreach($request->categories as $category) {
            $product->categories()->attach($category);
        }
        if($request->file('images')) {
            foreach($request->file('images') as $image) {
                $image_path = $image->store('upload', 'public');
                Image::create([
                    'image' => $image_path,
                    'product_id' => $product->id
                ]);
            }
        }
        return redirect()->route('admin.products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $categories = Category::all();
        return view('product.show', compact('product', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        dd($request->toArray());
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->code = $request->code;
        $product->currency = $request->currency;
        $product->is_published = $product->is_published ?? 'off';
        if($request->file('images')) {
            foreach($request->file('images') as $image) {
                $image_path = $image->store('upload', 'public');
                Image::create([
                    'image' => $image_path,
                    'product_id' => $product->id
                ]);
            }
        }
        $product->categories()->attach($request->category);
        $product->save();

        Session::flash('msg','Product successfully updated.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Session::flash('msg', 'Product was deleted!');
        $product->delete();
        return redirect()->back();
    }
}
