<?php

use Illuminate\Support\Facades\Route;
use Gegok12\UpcomingHolidays\Http\Controllers\Teacher\UpcomingHolidaysController;

// Required from the end of routes/teacher.php, so this inherits
// that portal's prefix/middleware group set up in
// app/Providers/RouteServiceProvider.php. Using [Controller::class,
// 'method'] array syntax bypasses that group's own ->namespace()
// setting, so the controller can stay in the package's own namespace.

Route::get('/upcoming-holidays', [UpcomingHolidaysController::class, 'index']);
