<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Fee\Http\Controllers\Accountant\FeesController as AccFeesController;
use Gegok12\Fee\Http\Controllers\Accountant\StructuralFeesController as AccStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Accountant\NonStructuralFeesController as AccNonStructuralFeesController;
use Gegok12\Fee\Http\Controllers\Accountant\FeePaymentController as AccFeePaymentController;

// The host's plugin-portal system has no "accountant" portal (only
// web/admin/teacher/student/api), but the host itself has a real, separate
// "accountant" role/URL-prefix/middleware predating the plugin system:
// app/Providers/RouteServiceProvider.php's mapAccountantRoutes() already
// wraps routes/accountant.php in prefix('accountant') +
// middleware(['web','auth','accountant']) + namespace('App\Http\Controllers\Accountant').
// This file is required manually from the tail of that same host
// routes/accountant.php (not via the automated per-portal wireRoutes()
// mechanism, since "accountant" isn't a valid plugin portal) so these
// routes inherit that wrap for free, at the correct /accountant/* prefix,
// with no double-prefixing. Bare routes, [Controller::class, 'method']
// array syntax bypasses the group's namespace so the controller can stay
// in the package's own namespace.

//fees
	//index
	Route::get( '/fees/list/{status}', [AccFeesController::class,'indexList']);
	Route::get( '/fees', [AccFeesController::class,'index']);
	//add
	Route::get( '/fee/add/list/{standardLink_id}', [AccStructuralFeesController::class,'index' ]);
	Route::get( '/fee/add', [AccStructuralFeesController::class,'create']);
	Route::post( '/fee/add', [AccStructuralFeesController::class,'store']);
	Route::post( '/fee/add/non_structural', [AccNonStructuralFeesController::class,'store']);
	//edit
	Route::get( '/fee/edit/list/{id}/{standardLink_id}', [AccFeesController::class,'show']);
	Route::get( '/fee/edit/{id}', [AccFeesController::class,'edit']);
	Route::post( '/fee/edit/{id}', [AccStructuralFeesController::class,'update']);
	Route::post( '/fee/edit/non_structural/{id}', [AccNonStructuralFeesController::class,'update']);
	//delete
	Route::get( '/fee/delete/{id}', [AccFeesController::class,'destroy']);

//fee detail
	//show
	Route::get( '/feedetail/show/list/{fee_id}', [AccFeePaymentController::class,'showList']);
	Route::get( '/feedetail/show/{fee_id}', [AccFeePaymentController::class,'show']);

	//update
	Route::get( '/feedetail/edit/list/{id}', [AccFeePaymentController::class,'edit']);
	Route::post( '/feedetail/edit/{id}', [AccFeePaymentController::class,'update']);

	//delete
	Route::get( '/feedetail/delete/{id}', [AccFeePaymentController::class,'destroy']);
