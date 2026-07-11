<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Inventory\Http\Controllers\Admin\StockProductController;
use Gegok12\Inventory\Http\Controllers\Admin\PurchaseOrderController;
use Gegok12\Inventory\Http\Controllers\Admin\SalesOrderController;
use Gegok12\Inventory\Http\Controllers\Admin\ReturnOrderController;
use Gegok12\Inventory\Http\Controllers\Admin\CategoryController;
use Gegok12\Inventory\Http\Controllers\Admin\SubCategoryController;
use Gegok12\Inventory\Http\Controllers\Admin\VendorController;
use Gegok12\Inventory\Http\Controllers\Admin\CategoryVendorController;
use Gegok12\Inventory\Http\Controllers\Admin\InventoryProductController;
use Gegok12\Inventory\Http\Controllers\Admin\ProductOwnershipController;
use Gegok12\Inventory\Http\Controllers\Admin\LocationController;
use Gegok12\Inventory\Http\Controllers\Admin\LocationProductController;
use Gegok12\Inventory\Http\Controllers\Admin\ProductConditionController;
use Gegok12\Inventory\Http\Controllers\Admin\ProductCodeController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

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

// Inventory (non-sellable/tracked assets)
Route::get('/category/show', [CategoryController::class, 'index']);
Route::get('/category/list', [CategoryController::class, 'showlist']);
Route::post('/category/add', [CategoryController::class, 'store']);
Route::get('/category/{id}/edit', [CategoryController::class, 'edit']);
Route::post('/category/{id}/update', [CategoryController::class, 'update']);
Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy']);

Route::get('/category/{id}/show', [SubCategoryController::class, 'index']);
Route::get('/category/{id}/list', [SubCategoryController::class, 'showlist']);
Route::post('/subcategory/add', [SubCategoryController::class, 'store']);
Route::get('/subcategory/{id}/edit', [SubCategoryController::class, 'edit']);
Route::post('/subcategory/{id}/update', [SubCategoryController::class, 'update']);
Route::delete('/subcategory/{id}/delete', [SubCategoryController::class, 'destroy']);

Route::get('/vendor/show', [VendorController::class, 'index']);
Route::get('/vendor/list', [VendorController::class, 'showlist']);
Route::get('/vendor/add', [VendorController::class, 'create']);
Route::post('/vendor/add', [VendorController::class, 'store']);
Route::get('/vendor/{id}/edit', [VendorController::class, 'edit']);
Route::get('/vendor/{id}/editshow', [VendorController::class, 'show']);
Route::post('/vendor/{id}/update', [VendorController::class, 'update']);
Route::delete('/vendor/{id}/delete', [VendorController::class, 'destroy']);

Route::get('/vendor/{id}/showlist', [CategoryVendorController::class, 'showlist']);
Route::get('/categoryvendor/{id}/add', [CategoryVendorController::class, 'create']);
Route::get('/categoryvendor/list/{id}', [CategoryVendorController::class, 'show']);
Route::get('/categoryvendor/{id}/show', [CategoryVendorController::class, 'index']);
Route::post('/categoryvendor/add', [CategoryVendorController::class, 'store']);
Route::delete('/categoryvendor/{id}/delete', [CategoryVendorController::class, 'destroy']);

Route::get('/product/show', [InventoryProductController::class, 'index']);
Route::get('/product/list', [InventoryProductController::class, 'showlist']);
Route::get('/product/category/list', [InventoryProductController::class, 'showcatlist']);
Route::get('/product/vendor/{id}/list', [InventoryProductController::class, 'showallvendor']);
Route::get('/product/add', [InventoryProductController::class, 'create']);
Route::post('/product/add', [InventoryProductController::class, 'store']);
Route::get('/product/{id}/edit', [InventoryProductController::class, 'edit']);
Route::get('/product/{id}/editshow', [InventoryProductController::class, 'editshow']);
Route::post('/product/{id}/update', [InventoryProductController::class, 'update']);
Route::get('/product/{id}/show', [InventoryProductController::class, 'show']);
Route::get('/product/{id}/detail', [InventoryProductController::class, 'detail']);
Route::delete('/product/{id}/delete', [InventoryProductController::class, 'destroy']);

Route::get('/productowner/{id}/show', [ProductOwnershipController::class, 'index']);
Route::get('/productowner/{id}/list', [ProductOwnershipController::class, 'showlist']);
Route::post('/productowner/add', [ProductOwnershipController::class, 'store']);
Route::get('/productowner/{id}/edit', [ProductOwnershipController::class, 'edit']);
Route::post('/productowner/{id}/update', [ProductOwnershipController::class, 'update']);
Route::delete('/productowner/{id}/delete', [ProductOwnershipController::class, 'destroy']);

Route::get('/location/show', [LocationController::class, 'index']);
Route::get('/location/list', [LocationController::class, 'showlist']);
Route::post('/location/add', [LocationController::class, 'store']);
Route::get('/location/{id}/edit', [LocationController::class, 'edit']);
Route::post('/location/{id}/update', [LocationController::class, 'update']);
Route::delete('/location/{id}/delete', [LocationController::class, 'destroy']);

Route::get('/locationproduct/{id}/show', [LocationProductController::class, 'index']);
Route::get('/locationproduct/{id}/list', [LocationProductController::class, 'showlist']);
Route::post('/locationproduct/add', [LocationProductController::class, 'store']);
Route::get('/locationproduct/{id}/edit', [LocationProductController::class, 'edit']);
Route::post('/locationproduct/{id}/update', [LocationProductController::class, 'update']);
Route::delete('/locationproduct/{id}/delete', [LocationProductController::class, 'destroy']);

Route::get('/productcondition/{id}/show', [ProductConditionController::class, 'index']);
Route::get('/productcondition/{id}/list', [ProductConditionController::class, 'showlist']);
Route::post('/productcondition/add', [ProductConditionController::class, 'store']);
Route::get('/productcondition/{id}/edit', [ProductConditionController::class, 'edit']);
Route::post('/productcondition/{id}/update', [ProductConditionController::class, 'update']);
Route::delete('/productcondition/{id}/delete', [ProductConditionController::class, 'destroy']);

Route::get('/product/{code}/info', [ProductCodeController::class, 'show']);
Route::get('/productcode/{id}/show', [ProductCodeController::class, 'index']);
Route::get('/productcode/ownershipmembers', [ProductCodeController::class, 'memberlist']);
Route::get('/productcode/activelocation', [ProductCodeController::class, 'locationlist']);
