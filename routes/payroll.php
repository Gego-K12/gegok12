<?php

use Illuminate\Support\Facades\Route;

// payroll
Route::get('/payroll/template',
    'PayrollTemplateController@index');
Route::get('/payroll/template/list', 'PayrollTemplateController@showlist');
Route::get('/payroll/template/create',
    'PayrollTemplateController@create');
Route::get('/payroll/template/showlist',
    'PayrollTemplateController@list');
Route::post('/payroll/template/add',
    'PayrollTemplateController@store');
Route::get('/payroll/template/{id}/edit', 'PayrollTemplateController@edit');
Route::get('/payroll/template/{id}/itemlist', 'PayrollTemplateController@itemlist');
Route::get('/payroll/template/{id}/editshow', 'PayrollTemplateController@editshow');
Route::post('/payroll/template/{id}/update', 'PayrollTemplateController@update');
Route::delete('/payroll/template/{id}/delete', 'PayrollTemplateController@destroy');

Route::get('/payroll/salary', 'PayrollSalaryController@index');
Route::get('/payroll/salary/list', 'PayrollSalaryController@showlist');
Route::get('/payroll/salary/create', 'PayrollSalaryController@create');
Route::get('/payroll/salary/showlist', 'PayrollSalaryController@list');

Route::get('/payroll/template/{id}/salary', 'PayrollSalaryController@detail');
Route::post('/payroll/salary/add', 'PayrollSalaryController@store');
Route::get('/payroll/salary/{id}/edit', 'PayrollSalaryController@edit');
Route::get('/payroll/salary/{id}/show', 'PayrollSalaryController@show');
Route::get('/payroll/salary/{id}/editshow',
    'PayrollSalaryController@editshow');
Route::post('/payroll/salary/{id}/update',
    'PayrollSalaryController@update');
Route::delete('/payroll/salary/{id}/delete', 'PayrollSalaryController@destroy');

Route::get('/payroll/payslip', 'PayrollController@index');
Route::get('/payroll/payslip/list', 'PayrollController@showlist');
Route::get('/payroll/payslip/create', 'PayrollController@create');
Route::get('/payroll/payslip/showlist', 'PayrollController@list');
Route::post('/payroll/payslip/details', 'PayrollController@details');
Route::post('/payroll/payslip/add', 'PayrollController@store');
Route::get('/payroll/payslip/{id}/view', 'PayrollController@downloadpayroll');
Route::get('/payroll/payslip/{id}/edit', 'PayrollController@edit');
Route::get('/payroll/payslip/{id}/show', 'PayrollController@show');
Route::get('/payroll/payslip/{id}/editshow',
    'PayrollController@editshow');
Route::post('/payroll/payslip/{id}/update',
    'PayrollController@update');
Route::delete('/payroll/payslip/{id}/delete', 'PayrollController@destroy');
Route::get('/payroll/payslip/getpayrollno', 'PayrollController@getpayrollnumber');

Route::get('/payroll/transaction', 'TransactionController@index');
Route::get('/payroll/transaction/getvoucherno', 'TransactionController@getvouchernumber');
Route::get('/payroll/transaction/list', 'TransactionController@showlist');
Route::get('/payroll/transaction/create', 'TransactionController@create');
Route::get('/payroll/transaction/showlist', 'TransactionController@list');
Route::get('/payroll/user/{id}/detail', 'TransactionController@detail');
Route::post('/payroll/transaction/add', 'TransactionController@store');
Route::get('/payroll/transaction/{id}/edit', 'TransactionController@edit');
Route::get('/payroll/transaction/{id}/show', 'TransactionController@show');
Route::get('/payroll/transaction/{id}/editshow',
    'TransactionController@editshow');
Route::post('/payroll/transaction/{id}/update',
    'TransactionController@update');
Route::delete('/payroll/transaction/{id}/delete', 'TransactionController@destroy');

Route::get('/exportUnpaidpayroll', 'ReportsController@exportUnpaidPayrolls');
Route::get('/unpaid/report', 'ReportsController@show');
Route::get('/unpaid/bank', 'ReportsController@showbank');
