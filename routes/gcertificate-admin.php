<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Certificate\Http\Controllers\Admin\CertificateTemplateController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

Route::get('/certificate/index', [CertificateTemplateController::class, 'list']);
Route::get('/certificate/list', [CertificateTemplateController::class, 'getcertificateList']);
Route::get('/certificate/template/{id}', [CertificateTemplateController::class, 'index']);
Route::get('/certificate/create', [CertificateTemplateController::class, 'create']);
Route::post('/certificate/store', [CertificateTemplateController::class, 'store']);
Route::get('/certificate/edit/{id}', [CertificateTemplateController::class, 'edit']);
Route::post('/certificate/update', [CertificateTemplateController::class, 'update']);
Route::get('/certificate/delete/{id}', [CertificateTemplateController::class, 'delete']);
Route::get('/certificate/classList', [CertificateTemplateController::class, 'getClassList']);
Route::get('/certificate/studentList/{standardLinklist}', [CertificateTemplateController::class, 'getStudentList']);
Route::get('/certificate/printcertificate/{id}', [CertificateTemplateController::class, 'printcertificate']);
Route::get('/certificate/show/{id}', [CertificateTemplateController::class, 'show']);
