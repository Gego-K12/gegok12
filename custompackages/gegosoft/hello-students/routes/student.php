<?php

use Illuminate\Support\Facades\Route;
use Gegosoft\HelloStudents\Http\Controllers\Student\HelloStudentsController;

// Required from the end of routes/student.php, so this inherits
// that portal's prefix/middleware group set up in
// app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's own ->namespace()
// setting, so the controller can stay in the package's own namespace.

Route::get('/hello-students', [HelloStudentsController::class, 'index']);
