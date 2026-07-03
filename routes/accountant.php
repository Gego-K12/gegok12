<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/structuralList', 'DashboardController@structuralList');
Route::post('/dashboard/structuralList', 'DashboardController@showStructuralList');

Route::get('/dashboard/tasklist/{task_flag}', 'DashboardController@list');
Route::get('/dashboard/task/count', 'DashboardController@listCount');

// feed
Route::get('/feeds', 'FeedController@index');

// birthday
Route::get('/dashboard/birthdayUser', 'BirthdayController@birthdayUser');
Route::get('/dashboard/showBirthday', 'BirthdayController@showBirthday');
Route::get('/dashboard/birthday', 'BirthdayController@birthday');
Route::post('/dashboard/birthday', 'BirthdayController@birthdayMessage');
Route::get('/dashboard/birthdayTeacher', 'BirthdayController@birthdayTeacher');
Route::get('/dashboard/showBirthdayTeacher', 'BirthdayController@showBirthdayTeacher');
Route::get('/dashboard/birthday/teacher', 'BirthdayController@birthdayCreate');
Route::post('/dashboard/birthday/teacher', 'BirthdayController@birthdayMessageTeacher');
Route::get('/dashboard/showWorkAnniversary', 'BirthdayController@showWorkAnniversary');
Route::get('/dashboard/workAnniversary/list', 'BirthdayController@workAnniversary');
Route::get('/dashboard/workAnniversary', 'BirthdayController@workAnniversaryCreate');
Route::post('/dashboard/workAnniversary', 'BirthdayController@workAnniversaryMessage');

// calendar
Route::get('/events', 'EventsController@index');
Route::get('/events/show', 'EventsController@events');
Route::get('/events/details/{id}', 'EventsController@details');
Route::get('/events/show/details/{id}', 'EventsController@show');
Route::get('/events/showdetails/{id}', 'EventsController@showdetails');

// holiday
Route::get('/holidays/list', 'HolidaysController@list');
Route::get('/holidays', 'HolidaysController@index');

// noticeboard
Route::get('/notices', 'NoticeBoardController@index');
Route::get('/notice/list', 'NoticeBoardController@list');
Route::get('/notice/show/list', 'NoticeBoardController@showList');

// task
// add
Route::get('/task/add/list', 'TaskController@list');
Route::get('/tasks', 'TaskController@index');
Route::get('/task/add', 'TaskController@create');
Route::post('/task/add', 'TaskController@store');

// index
Route::get('/task/list', 'TaskController@showlist');
Route::post('/task/completed', 'TaskController@changestatus');

// show
Route::get('/task/show/{id}', 'TaskController@show');

// edit
Route::get('/task/edit/list/{id}', 'TaskController@editList');
Route::get('/task/edit/{id}', 'TaskController@edit');
Route::post('/task/edit/{id}', 'TaskController@update');

// snooze
Route::post('/task/snooze/{id}', 'TaskController@snooze');

// delete
Route::get('/task/{id}/delete', 'TaskController@destroy');

// activity log
Route::get('/activity', 'ActivityLogController@index');

// change password
Route::get('/changepassword', 'UserProfileController@ChangePassword');
Route::post('/changepassword', 'UserProfileController@updateChangePassword');

// change avatar
Route::get('/changeavatar', 'UserProfileController@changeavatar');
Route::post('/changeavatar', 'UserProfileController@updatechangeavatar');
Route::get('/getavatar', 'UserProfileController@getavatar');

// notification
Route::get('/notification/list', 'NotificationController@indexList');
Route::get('/notifications', 'NotificationController@index');
Route::post('/notification/read', 'NotificationController@store');
Route::get('/notification/showList', 'NotificationController@showList');
