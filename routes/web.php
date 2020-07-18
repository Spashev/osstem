<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->prefix('~admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/','AdminController@index')->name('index');
    // User
    Route::get('/users', 'ClientController@users')->name('users');
    Route::post('/user/create', 'ClientController@userStore')->name('user.create');
    Route::get('/user/create', 'ClientController@create')->name('user.create.form');
    Route::get('/user/{user}/show', 'ClientController@show')->name('user.show');
    Route::get('/user/{user}/edit', 'ClientController@edit')->name('user.edit');
    Route::get('/user/{user}/delete', 'ClientController@destroy')->name('user.delete');
    // Category
    Route::get('/categories', 'CategoryController@index')->name('category');
    Route::get('/category/create', 'CategoryController@create')->name('category.create');
    Route::post('/category/create', 'CategoryController@store')->name('category.store');
    // Order
    Route::get('/orders', 'AdminController@orders')->name('orders');
    // Product
    Route::get('/products', 'ProductController@index')->name('products');
    Route::get('/product/create', 'ProductController@create')->name('product.create');
    Route::post('/product/create', 'ProductController@store')->name('product.store');
});
