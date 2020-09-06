<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('admin/login', 'Admin\LoginController@login')->name('login.admin');
Route::view('admin/login', 'admin.login')->name('admin.login');
Route::middleware('auth')->prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/', 'AdminController@index')->name('index');
    // User
    Route::get('/users', 'ClientController@users')->name('users');
    Route::post('/user/create', 'ClientController@userStore')->name('user.create');
    Route::get('/user/create', 'ClientController@create')->name('user.create.form');
    Route::get('/user/{user}/show', 'ClientController@show')->name('user.show');
    Route::get('/user/{user}/edit', 'ClientController@edit')->name('user.edit');
    Route::put('/user/{user}/edit', 'ClientController@update')->name('user.update');
    Route::get('/user/{user}/delete', 'ClientController@destroy')->name('user.delete');
    // Category
    Route::get('/categories', 'CategoryController@index')->name('category');
    Route::get('/category/{category}/delete', 'CategoryController@delete')->name('category.delete');
    Route::get('/category/{category}/edit', 'CategoryController@edit')->name('category.edit');
    Route::get('/category/{category}/show', 'CategoryController@show')->name('category.show');
    Route::get('/category/create', 'CategoryController@create')->name('category.create');
    Route::post('/category/create', 'CategoryController@store')->name('category.store');
    // Order
    Route::get('/orders', 'AdminController@orders')->name('orders');
    //Excel
    Route::get('excel', 'ExcelController@index')->name('excel');
    Route::get('excel/create', 'ExcelController@create')->name('excel.create');
    Route::post('excel/save', 'ExcelController@save')->name('excel.save');
    Route::post('excel/upload', 'ExcelController@upload')->name('excel.upload');
    Route::get('excel/table', 'ExcelController@table')->name('excel.table');
    Route::get('payment/{id}/edit', 'ExcelController@edit')->name('excel.edit');
    Route::get('payment/{id}/update', 'ExcelController@update')->name('excel.update');
    Route::get('payment/{payment}/delete', 'ExcelController@delete')->name('excel.delete');
    Route::get('/payments', 'ExcelController@payment')->name('excel.payments');
    //Manager
    Route::get('/managers', 'ManagerController@manager')->name('excel.managers');
    Route::get('/manager/{id}/show', 'ManagerController@show')->name('manager.show');
    Route::get('/manager/{manager}/delete', 'ManagerController@managerDelete')->name('manager.delete');
    Route::get('/manager/{manager}/edit', 'ManagerController@managerEdit')->name('manager.edit');
    Route::post('manager', 'ManagerController@save')->name('manager.save');
    //Customer
    Route::get('/customers', 'CustomerController@customer')->name('excel.customers');
    Route::post('/customer', 'CustomerController@store')->name('customer.save');
    Route::get('/customer/{customer}', 'CustomerController@show')->name('customer.show');
    Route::get('/customer/csv/{id}', 'CustomerController@get_csv')->name('customer.csv');
    Route::get('/customer/{id}/invoice', 'CustomerController@invoice')->name('customer.invoice');
    // Product
    Route::get('/products', 'ProductController@index')->name('products');
    Route::get('/product/create', 'ProductController@create')->name('product.create');
    Route::post('/product/create', 'ProductController@store')->name('product.store');
    Route::get('/product/{product}/edit', 'ProductController@edit')->name('product.edit');
    Route::put('/product/{product}/edit', 'ProductController@update')->name('product.update');
    Route::get('/product/{product}/show', 'ProductController@show')->name('product.show');
    Route::get('/product/{product}/delete', 'ProductController@destroy')->name('product.delete');
    //Roles
    Route::get('/roles', 'RoleController@index')->name('roles');
});