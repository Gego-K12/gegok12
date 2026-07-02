<?php

//navigation drop-down
Route::get('/list/academicyear','NavigationController@list');
Route::post('/academicyear/index','NavigationController@index');

//navigation notification
Route::get('/notification/showList', 'NotificationController@showList');

//dashboard
Route::get( '/dashboard/event', 'DashboardController@event' );

//dashboard-birthday
Route::get( '/dashboard/showBirthday', 'BirthdayController@showBirthday' );
Route::get( '/dashboard/showBirthdayTeacher', 'BirthdayController@showBirthdayTeacher' );
Route::get( '/dashboard/showWorkAnniversary', 'BirthdayController@showWorkAnniversary' );

//absentees - dashboard
Route::get( '/absentees/student', 'AttendanceController@student' );
Route::get( '/absentees/student/list', 'AttendanceController@studentList' );
Route::get( '/absentees/staff', 'StaffAttendanceController@staff' );
Route::get( '/absentees/staff/list', 'StaffAttendanceController@stafflist' );

//academics
	//index
	Route::get( '/academics', 'AcademicYearController@index' );
	//add
	Route::get( '/academic/add', 'AcademicYearController@create' );
	Route::post( '/academic/add', 'AcademicYearController@store' );
	//edit
	Route::get( '/academic/edit/{id}', 'AcademicYearController@edit' );
	Route::post( '/academic/update/{id}', 'AcademicYearController@update' );
	Route::post( '/academic/updateStatus/{id}', 'AcademicYearController@updateStatus' );
	//delete
	Route::get( '/academic/delete/{id}', 'AcademicYearController@destroy' );

//standard setup
Route::get( '/standard/create', 'StandardController@create' );
Route::post( '/standard/create', 'StandardController@add' );