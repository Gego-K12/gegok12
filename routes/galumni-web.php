<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Alumni\Http\Controllers\Alumni\DashboardController;
use Gegok12\Alumni\Http\Controllers\Alumni\AlumniAddController;
use Gegok12\Alumni\Http\Controllers\Alumni\AlumniEditController;
use Gegok12\Alumni\Http\Controllers\Alumni\EventsController;

Route::group([
          'prefix' => 'alumni',
        'middleware' => ['web','auth', 'alumni'],

    ], function () {
               Route::get('/dashboard', [DashboardController::class, 'index']);

                //admin-side
                Route::get( '/find', [AlumniAddController::class,'find']);
                Route::get( '/index', [AlumniAddController::class,'index']);
                Route::get( '/getdate', [AlumniAddController::class,'getDate']);
                Route::get( '/add', [AlumniAddController::class,'create']);
                Route::post( '/add', [AlumniAddController::class,'store']);

                Route::post( '/add/validationProfile', [AlumniAddController::class,'validationProfile']);
                Route::post( '/add/validationEducation', [AlumniAddController::class,'validationEducation']);
                Route::post( '/add/validationJob', [AlumniAddController::class,'validationJob']);
                Route::post( '/add/validationContact', [AlumniAddController::class,'validationContact']);

                Route::get( '/show/details/{name}', [AlumniAddController::class,'showDetails']);
                Route::get( '/show/{name}', [AlumniAddController::class,'show']);

                //edit
                Route::get( '/editAlumni', [AlumniEditController::class,'editAlumni']);
                Route::get( '/edit', [AlumniEditController::class,'edit']);
                Route::post( '/edit/validationProfile', [AlumniEditController::class,'editValidationProfile']);
                Route::post( '/edit/validationEducation', [AlumniEditController::class,'editValidationEducation']);
                Route::post( '/edit/validationJob', [AlumniEditController::class,'editValidationJob']);
                Route::post( '/edit/validationContact', [AlumniEditController::class,'editvalidationContact']);
                Route::post( '/edit', [AlumniEditController::class,'update']);

                //calendar
                Route::get('/events',[EventsController::class,'index']);
                Route::get( '/events/show', [EventsController::class,'events' ]);
                Route::get( '/events/details/{id}', [EventsController::class,'details' ]);
                Route::get( '/events/showdetails/{id}', [EventsController::class,'showdetails' ]);

       });
