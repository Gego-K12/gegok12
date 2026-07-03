<?php

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/tasklist/{task_flag}', 'DashboardController@list');
Route::get('/dashboard/task/count', 'DashboardController@listCount');
// category
Route::get('/bookscategory/index', 'BookCategoryController@index');
Route::get('/bookscategory/add', 'BookCategoryController@create');
Route::post('/bookscategory/add', 'BookCategoryController@store');
Route::get('/bookscategory/show/{id}', 'BookCategoryController@show');
Route::get('/bookscategory/edit/{id}', 'BookCategoryController@edit');
Route::post('/bookscategory/update/{id}', 'BookCategoryController@update');
Route::get('/bookscategory/delete/{id}', 'BookCategoryController@destroy');
Route::get('/bookscategory/count', 'BookCategoryController@categorycount');
// books
Route::get('/bookscategory/list', 'BookController@list');
Route::get('/books/index', 'BookController@index');
Route::get('/books/add', 'BookController@create');
Route::post('/books/add', 'BookController@store');
// edit
Route::get('/books/show/{id}', 'BookController@show');
Route::get('/books/edit/{id}', 'BookController@edit');
Route::post('/books/update/{id}', 'BookController@update');
// delete
Route::get('/books/delete/{id}', 'BookController@destroy');
// books lending
Route::get('/booklending/index', 'BookLendingController@index');
Route::get('/booklending/add', 'BookLendingController@create');
Route::post('/booklending/add', 'BookLendingController@store');
Route::get('/booklending/show/{id}', 'BookLendingController@show');
Route::get('/booklending/edit/{id}', 'BookLendingController@edit');
Route::post('/booklending/update/{id}', 'BookLendingController@update');
Route::get('/booklending/delete/{id}', 'BookLendingController@destroy');
Route::get('/booklending/return', 'BookLendingController@bookreturn');

// holiday
Route::get('/holidays/list', 'HolidaysController@list');
Route::get('/holidays', 'HolidaysController@index');
// activity log
Route::get('/activity', 'ActivityLogController@index');

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

// library card import teacher
Route::get('/import/library', 'LibraryImportController@create');
Route::post('/import/library', 'LibraryImportController@import');
Route::get('/students', 'StudentController@index');
Route::get('/students/find', 'StudentController@find');
Route::get('/downloadformat', 'LibraryImportController@downloadFormat');
Route::get('/teachers/find', 'TeacherListController@find');
Route::get('/teachers', 'TeacherListController@index');
Route::get('/staffs/find', 'StaffController@find');
Route::get('/staffs', 'StaffController@index');
