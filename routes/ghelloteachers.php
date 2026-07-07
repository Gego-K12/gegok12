<?php

use Gegok12\HelloTeachers\Http\Controllers\Teacher\QuoteController;
use Illuminate\Support\Facades\Route;

// Required from the end of routes/teacher.php by the plugin installer, so
// these inherit the teacher portal's prefix('teacher') +
// middleware(['web','auth','teacher']) group set up in
// app/Providers/RouteServiceProvider.php.

Route::get('/helloteachers', [QuoteController::class, 'index']);
Route::get('/helloteachers/quote', [QuoteController::class, 'random']);
