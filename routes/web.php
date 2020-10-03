<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('login', 'Admin\LoginController@login')->name('login.admin');
Route::view('login', 'admin.login')->name('admin.login');
Route::middleware('auth')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/home', 'AdminController@index')->name('index');
    // User
    Route::get('/users', 'ClientController@users')->name('users');
    Route::post('/user/create', 'ClientController@userStore')->name('user.create');
    Route::get('/user/create', 'ClientController@create')->name('user.create.form');
    Route::get('/user/{user}/show', 'ClientController@show')->name('user.show');
    Route::get('/user/{user}/edit', 'ClientController@edit')->name('user.edit');
    Route::put('/user/{user}/edit', 'ClientController@update')->name('user.update');
    Route::get('/user/{user}/delete', 'ClientController@destroy')->name('user.delete');
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
    //Analyzer
    Route::get('analyzer', 'AnalyzerController@index')->name('analyze');
    Route::get('/analyzer/get', 'AnalyzerController@getData');
    Route::post('analyzer/get-analyze', 'AnalyzerController@getFilter');
    //Manager
    Route::get('/managers', 'ManagerController@manager')->name('excel.managers');
    Route::get('/manager/{id}/show', 'ManagerController@show')->name('manager.show');
    Route::get('/manager/{manager}/delete', 'ManagerController@managerDelete')->name('manager.delete');
    Route::put('/manager/{manager}/', 'ManagerController@update')->name('manager.update');
    Route::post('manager', 'ManagerController@save')->name('manager.save');
    //Customer
    Route::get('/customers', 'CustomerController@customer')->name('excel.customers');
    Route::post('/customer', 'CustomerController@store')->name('customer.save');
    Route::get('/customer/{customer}', 'CustomerController@show')->name('customer.show');
    Route::get('/customer/csv/{id}', 'CustomerController@get_csv')->name('customer.csv');
    Route::get('/customer/{id}/invoice', 'CustomerController@invoice')->name('customer.invoice');
    //Update
    Route::get('/payment_update', 'ExcelController@download')->name('download');
});
Route::get('import', 'Admin\ExcelController@import');