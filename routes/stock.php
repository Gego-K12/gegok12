<?php

use Illuminate\Support\Facades\Route;
use Gegok12\Inventory\Http\Controllers\Admin\StockProductController;
use Gegok12\Inventory\Http\Controllers\Admin\PurchaseOrderController;
use Gegok12\Inventory\Http\Controllers\Admin\SalesOrderController;
use Gegok12\Inventory\Http\Controllers\Admin\ReturnOrderController;

// Stock Keeper's own portal, reusing the exact same inventory-package
// controllers admin's "Stocks" sidebar section already uses (see
// custompackages/gegok12/inventory/routes/admin.php) -- these routes and
// their views (resources/views/admin/stock/**) are shared verbatim between
// the two portals; each view's @extends picks layouts.stock.layout vs
// layouts.admin.layout based on which prefix the request came in on.
// [Controller::class, 'method'] array syntax bypasses this group's
// ->namespace() setting, so the controller can stay in the package's own
// namespace.

// Stock (sellable products: purchase/sales/return-order transactions)
Route::get('/products', [StockProductController::class, 'showproducts']);
Route::get('/productlist', [StockProductController::class, 'productlist']);
Route::get('/stockproduct/show', [StockProductController::class, 'index']);
Route::get('/stockproduct/list', [StockProductController::class, 'showlist']);
Route::get('/stockproduct/category/list', [StockProductController::class, 'showcatlist']);
Route::get('/stockproduct/vendor/{id}/list', [StockProductController::class, 'showallvendor']);
Route::get('/stockproduct/add', [StockProductController::class, 'create']);
Route::post('/stockproduct/add', [StockProductController::class, 'store']);
Route::get('/stockproduct/{id}/edit', [StockProductController::class, 'edit']);
Route::get('/stockproduct/{id}/editshow', [StockProductController::class, 'editshow']);
Route::post('/stockproduct/{id}/update', [StockProductController::class, 'update']);
Route::get('/stockproduct/{id}/show', [StockProductController::class, 'show']);
Route::get('/stockproduct/{id}/detail', [StockProductController::class, 'detail']);
Route::delete('/stockproduct/{id}/delete', [StockProductController::class, 'destroy']);

// Purchase
Route::get('/purchase/show', [PurchaseOrderController::class, 'index']);
Route::get('/purchase/list', [PurchaseOrderController::class, 'showlist']);
Route::get('/purchase/standard/list', [PurchaseOrderController::class, 'standardlist']);
Route::get('/purchase/student/{id}/list', [PurchaseOrderController::class, 'studentlist']);
Route::get('/purchase/add', [PurchaseOrderController::class, 'create']);
Route::post('/purchase/add', [PurchaseOrderController::class, 'store']);
Route::get('/purchase/{id}/edit', [PurchaseOrderController::class, 'edit']);
Route::get('/purchase/{id}/editshow', [PurchaseOrderController::class, 'editshow']);
Route::post('/purchase/{id}/update', [PurchaseOrderController::class, 'update']);
Route::get('/purchase/{id}/show', [PurchaseOrderController::class, 'show']);
Route::get('/purchase/{id}/detail', [PurchaseOrderController::class, 'detail']);
Route::delete('/purchase/{id}/delete', [PurchaseOrderController::class, 'destroy']);

// Sales
Route::get('/sales/show', [SalesOrderController::class, 'index']);
Route::get('/sales/list', [SalesOrderController::class, 'showlist']);
Route::get('/sales/standard/list', [SalesOrderController::class, 'standardlist']);
Route::get('/sales/student/{id}/list', [SalesOrderController::class, 'studentlist']);
Route::get('/sales/{product_id}/add', [SalesOrderController::class, 'create']);
Route::post('/sales/{product_id}/add', [SalesOrderController::class, 'store']);
Route::get('/sales/{id}/edit', [SalesOrderController::class, 'edit']);
Route::get('/sales/{id}/editshow', [SalesOrderController::class, 'editshow']);
Route::post('/sales/{id}/update', [SalesOrderController::class, 'update']);
Route::get('/sales/{id}/show', [SalesOrderController::class, 'show']);
Route::get('/sales/{id}/detail', [SalesOrderController::class, 'detail']);
Route::delete('/sales/{id}/delete', [SalesOrderController::class, 'destroy']);

// Order returns
Route::get('/returnorder/show', [ReturnOrderController::class, 'index']);
Route::get('/returnorder/list', [ReturnOrderController::class, 'showlist']);
Route::get('/returnorder/standard/list', [ReturnOrderController::class, 'standardlist']);
Route::get('/returnorder/student/{id}/list', [ReturnOrderController::class, 'studentlist']);
Route::get('/returnorder/{product_id}/add', [ReturnOrderController::class, 'create']);
Route::post('/returnorder/{product_id}/add', [ReturnOrderController::class, 'store']);
Route::get('/returnorder/{id}/edit', [ReturnOrderController::class, 'edit']);
Route::get('/returnorder/{id}/editshow', [ReturnOrderController::class, 'editshow']);
Route::post('/returnorder/{id}/update', [ReturnOrderController::class, 'update']);
Route::get('/returnorder/{id}/show', [ReturnOrderController::class, 'show']);
Route::get('/returnorder/{id}/detail', [ReturnOrderController::class, 'detail']);
Route::delete('/returnorder/{id}/delete', [ReturnOrderController::class, 'destroy']);
