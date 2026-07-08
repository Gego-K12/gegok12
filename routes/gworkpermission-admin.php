<?php

use Gegok12\WorkPermission\Http\Controllers\Admin\WorkPermissionController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/admin.php, so this inherits the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

Route::get('/workpermissions', [WorkPermissionController::class, 'index']);
