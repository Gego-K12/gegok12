<?php

use Gegok12\Fee\Http\Controllers\Accountant\FeePaymentController as AccFeePaymentController;
use Gegok12\Fee\Http\Controllers\Accountant\FeesController as AccFeesController;
use Gegok12\Fee\Http\Controllers\Accountant\NonStructuralFeesController as AccNonStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Accountant\StructuralFeesController as AccStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Admin\FeeGroupController;
use Gegok12\Fee\Http\Controllers\Admin\FeePaymentController;
use Gegok12\Fee\Http\Controllers\Admin\FeesController;
// Accountant

use Gegok12\Fee\Http\Controllers\Admin\NonStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Admin\StructuralFeesController;
use Gegok12\Fee\Http\Controllers\Admin\StudentFeesController;
use Illuminate\Support\Facades\Route;

// Admin
Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth', 'schooladmin', 'privilegeconditions'],
], function () {
    // fees
    // index
    Route::get('/fees/list/{status}', [FeesController::class, 'indexList']);
    Route::get('/fees', [FeesController::class, 'index']);
    // add
    Route::get('/fee/add/list/{standardLink_id}', [StructuralFeesController::class, 'index']);
    Route::get('/fee/add', [StructuralFeesController::class, 'create']);
    Route::post('/fee/add', [StructuralFeesController::class, 'store']);
    Route::post('/fee/add/non_structural', [NonStructuralFeesController::class, 'store']);
    // edit
    Route::get('/fee/edit/list/{id}/{standardLink_id}', [FeesController::class, 'show']);
    Route::get('/fee/edit/{id}', [FeesController::class, 'edit']);
    Route::post('/fee/edit/{id}', [StructuralFeesController::class, 'update']);
    Route::post('/fee/edit/non_structural/{id}', [NonStructuralFeesController::class, 'update']);
    // delete
    Route::get('/fee/delete/{id}', [FeesController::class, 'destroy']);

    // fees payment
    // index
    Route::get('/feedetails/list', [FeePaymentController::class, 'index']);
    // add
    Route::post('/feedetail/add', [FeePaymentController::class, 'store']);
    // show
    Route::get('/feedetail/show/list/{fee_id}', [FeePaymentController::class, 'showList']);
    Route::get('/feedetail/show/{fee_id}', [FeePaymentController::class, 'show']);
    // update
    Route::get('/feedetail/edit/list/{id}', [FeePaymentController::class, 'edit']);
    Route::post('/feedetail/edit/{id}', [FeePaymentController::class, 'update']);
    // delete
    Route::get('/feedetail/delete/{id}', [FeePaymentController::class, 'destroy']);

    // feegroup
    // index
    Route::get('/feegroup', [FeeGroupController::class, 'index']);
    Route::get('/feegroup/list', [FeeGroupController::class, 'feegrouplist']);
    // add
    Route::get('/feegroup/add', [FeeGroupController::class, 'create']);
    Route::post('/feegroup/add', [FeeGroupController::class, 'store']);
    // edit
    Route::get('/feegroup/show/{id}', [FeeGroupController::class, 'show']);
    Route::get('/feegroup/edit/{id}', [FeeGroupController::class, 'edit']);
    Route::post('/feegroup/edit/{id}', [FeeGroupController::class, 'update']);
    // delete
    Route::get('/feegroup/delete/{id}', [FeeGroupController::class, 'destroy']);

    // student fees
    // index
    Route::get('/student/fee/list/{id}/{name}', [StudentFeesController::class, 'index']);

    // assign
    Route::post('/student/fee/assign', [StudentFeesController::class, 'assign']);
    Route::post('/student/fee/reset', [StudentFeesController::class, 'reset']);

    Route::get('/student/feepayment/add/list/{id}', [StudentFeesController::class, 'list']);
    Route::post('/student/feepayment/add', [StudentFeesController::class, 'store']);
    Route::post('/student/feepayment/edit/{id}', [StudentFeesController::class, 'update']);
});

// Accountant
Route::group([
    'prefix' => 'accountant',
    'middleware' => ['web', 'auth', 'accountant'],
], function () {

    // fees
    // index
    Route::get('/fees/list/{status}', [AccFeesController::class, 'indexList']);
    Route::get('/fees', [AccFeesController::class, 'index']);
    // add
    Route::get('/fee/add/list/{standardLink_id}', [AccStructuralFeesController::class, 'index']);
    Route::get('/fee/add', [AccStructuralFeesController::class, 'create']);
    Route::post('/fee/add', [AccStructuralFeesController::class, 'store']);
    Route::post('/fee/add/non_structural', [AccNonStructuralFeesController::class, 'store']);
    // edit
    Route::get('/fee/edit/list/{id}/{standardLink_id}', [AccFeesController::class, 'show']);
    Route::get('/fee/edit/{id}', [AccFeesController::class, 'edit']);
    Route::post('/fee/edit/{id}', [AccStructuralFeesController::class, 'update']);
    Route::post('/fee/edit/non_structural/{id}', [AccNonStructuralFeesController::class, 'update']);
    // delete
    Route::get('/fee/delete/{id}', [AccFeesController::class, 'destroy']);

    // fee detail
    // show
    Route::get('/feedetail/show/list/{fee_id}', [AccFeePaymentController::class, 'showList']);
    Route::get('/feedetail/show/{fee_id}', [AccFeePaymentController::class, 'show']);

    // update
    Route::get('/feedetail/edit/list/{id}', [AccFeePaymentController::class, 'edit']);
    Route::post('/feedetail/edit/{id}', [AccFeePaymentController::class, 'update']);

    // delete
    Route::get('/feedetail/delete/{id}', [AccFeePaymentController::class, 'destroy']);
});
