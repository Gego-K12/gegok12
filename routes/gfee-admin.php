<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Fee\Http\Controllers\Admin\FeesController;
use Gegok12\Fee\Http\Controllers\Admin\StructuralFeesController;
use Gegok12\Fee\Http\Controllers\Admin\NonStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Admin\FeePaymentController;
use Gegok12\Fee\Http\Controllers\Admin\FeeGroupController;
use Gegok12\Fee\Http\Controllers\Admin\StudentFeesController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

//fees
	//index
	Route::get( '/fees/list/{status}', [FeesController::class,'indexList']);
	Route::get( '/fees', [FeesController::class,'index']);
	//add
	Route::get( '/fee/add/list/{standardLink_id}', [StructuralFeesController::class,'index']);
	Route::get( '/fee/add', [StructuralFeesController::class,'create']);
	Route::post( '/fee/add', [StructuralFeesController::class,'store']);
	Route::post( '/fee/add/non_structural', [NonStructuralFeesController::class,'store']);
	//edit
	Route::get( '/fee/edit/list/{id}/{standardLink_id}', [FeesController::class,'show']);
	Route::get( '/fee/edit/{id}', [FeesController::class,'edit']);
	Route::post( '/fee/edit/{id}', [StructuralFeesController::class,'update']);
	Route::post( '/fee/edit/non_structural/{id}', [NonStructuralFeesController::class,'update']);
	//delete
	Route::get( '/fee/delete/{id}', [FeesController::class,'destroy']);

//fees payment
	//index
	Route::get( '/feedetails/list', [FeePaymentController::class,'index']);
	//add
	Route::post( '/feedetail/add', [FeePaymentController::class,'store']);
	//show
	Route::get( '/feedetail/show/list/{fee_id}', [FeePaymentController::class,'showList']);
	Route::get( '/feedetail/show/{fee_id}', [FeePaymentController::class,'show' ]);
	//update
	Route::get( '/feedetail/edit/list/{id}', [FeePaymentController::class,'edit']);
	Route::post( '/feedetail/edit/{id}', [FeePaymentController::class,'update']);
	//delete
	Route::get( '/feedetail/delete/{id}', [FeePaymentController::class,'destroy']);

	//feegroup
	//index
	Route::get( '/feegroup', [FeeGroupController::class,'index']);
	Route::get( '/feegroup/list', [FeeGroupController::class,'feegrouplist']);
	//add
	Route::get( '/feegroup/add', [FeeGroupController::class,'create']);
	Route::post( '/feegroup/add', [FeeGroupController::class,'store']);
	//edit
	Route::get( '/feegroup/show/{id}', [FeeGroupController::class,'show']);
	Route::get( '/feegroup/edit/{id}', [FeeGroupController::class,'edit']);
	Route::post( '/feegroup/edit/{id}', [FeeGroupController::class,'update']);
	//delete
	Route::get( '/feegroup/delete/{id}', [FeeGroupController::class,'destroy']);

//student fees
	//index
	Route::get( '/student/fee/list/{id}/{name}', [StudentFeesController::class,'index']);

	//assign
	Route::post( '/student/fee/assign', [StudentFeesController::class,'assign']);
	Route::post( '/student/fee/reset', [StudentFeesController::class,'reset']);

	Route::get( '/student/feepayment/add/list/{id}', [StudentFeesController::class,'list']);
	Route::post( '/student/feepayment/add', [StudentFeesController::class,'store']);
	Route::post( '/student/feepayment/edit/{id}', [StudentFeesController::class,'update']);
