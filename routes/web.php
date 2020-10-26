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
    Route::post('excel/filter', 'ExcelController@filter')->name('excel.filter');
    Route::post('excel/table', 'ExcelController@filters')->name('excel.filters');
    Route::get('payment/{id}/edit', 'ExcelController@edit')->name('excel.edit');
    Route::post('payment/{id}/update', 'ExcelController@update')->name('excel.update');
    Route::get('payment/{payment}/delete', 'ExcelController@delete')->name('excel.delete');
    Route::get('/payments', 'ExcelController@payment')->name('excel.payments');
    //Sms
    Route::get('sms', 'SmsController@index')->name('sms');
    Route::get('sms/history', 'SmsController@history')->name('sms.history');
    Route::get('send-sms/history', 'SmsController@historyPaginations');
    Route::get('sms/history/{customer}', 'SmsController@customerSms');
    Route::get('sms/history-phone/{notification}', 'SmsController@phoneSms');
    Route::get('sms/get', 'SmsController@getData');
    Route::get('sms/get-customer/{customer}', 'SmsController@customer');
    Route::get('sms/get-region/{region}', 'SmsController@region');
    Route::post('sms/get-date', 'SmsController@data');
    Route::post('sms/contract-data', 'SmsController@contractData');
    Route::post('sms/customer-data', 'SmsController@customerData');
    Route::post('sms/status', 'SmsController@smsStatus');
    Route::post('send/sms', 'SmsController@sendSms');
    Route::get('send/sms/{region}', 'SmsController@sendRegionSms');
    //Notification
    Route::get('notify', 'NotificationContrller@index')->name('notify');
    Route::get('notify/get', 'NotificationContrller@getData');
    Route::get('notify/get-customer/{customer}', 'NotificationContrller@customer');
    Route::get('notify/get-region/{region}', 'NotificationContrller@region');
    Route::post('notify/get-date', 'NotificationContrller@data');
    Route::post('notify/contract-data', 'NotificationContrller@contractData');
    Route::post('notify/customer-data', 'NotificationContrller@customerData');
    Route::post('notify/status', 'NotificationContrller@smsStatus');
    Route::post('send/notify', 'NotificationContrller@sendSms');
    Route::get('send/notify/{region}', 'NotificationContrller@sendRegionSms');
    //Analyzer
    Route::get('analyzer', 'AnalyzerController@index')->name('analyze');
    Route::get('analyzer/upload', 'AnalyzerController@upload')->name('analyze.upload');
    Route::get('/analyzer/get', 'AnalyzerController@getData');
    Route::post('analyzer/get-analyze', 'AnalyzerController@getFilter');
    Route::get('get-manager/{manager}', 'AnalyzerController@getManager');
    Route::get('get-contract/{customer}', 'AnalyzerController@getContract');
    Route::get('get-payments/{contract}', 'AnalyzerController@getPayments');
    Route::get('get-region/{region}', 'AnalyzerController@getRegion');
    Route::post('get-date/', 'AnalyzerController@getDatePayments');
    //Manager
    Route::get('/managers', 'ManagerController@manager')->name('excel.managers');
    Route::get('/manager/{id}/show', 'ManagerController@show')->name('manager.show');
    Route::get('/manager/{manager}/delete', 'ManagerController@managerDelete')->name('manager.delete');
    Route::put('/manager/{manager}/', 'ManagerController@update')->name('manager.update');
    Route::post('manager', 'ManagerController@save')->name('manager.save');
    //Customer
    Route::get('/customers', 'CustomerController@customer')->name('excel.customers');
    Route::post('/customer', 'CustomerController@store')->name('customer.save');
    Route::get('/customer/upload', 'CustomerController@upload')->name('customers.upload');
    Route::get('/customer/{customer}', 'CustomerController@show')->name('customer.show');
    Route::get('/customer/csv/{id}', 'CustomerController@get_csv')->name('customer.csv');
    Route::get('/customer/{id}/invoice', 'CustomerController@invoice')->name('customer.invoice');
    Route::get('/customer/{id}/edit', 'CustomerController@edit')->name('customer.edit');
    Route::post('/customer/{id}/update', 'CustomerController@update')->name('customer.update');
    Route::get('/customer/{customer}/delete', 'CustomerController@delete')->name('customer.delete');
    //Update
    Route::get('/payment_update', 'ExcelController@download')->name('download');
    Route::post('import', 'ExcelController@import')->name('import');
});