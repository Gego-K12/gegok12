<?php

use Illuminate\Support\Facades\Route;


Route::get('/dashboard', 'DashboardController@index');
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
Route::get('/events/showdetails/{id}', 'EventsController@showdetails');
Route::get('/events/details/{id}', 'EventsController@details');

// visitor-log
Route::get('/visitorlog', 'VisitorLogController@index');
Route::get('/visitorlog/showlist', 'VisitorLogController@showlist');
Route::get('/visitorlog/list', 'VisitorLogController@list');
Route::get('/visitorlog/add', 'VisitorLogController@create');
Route::post('/visitorlog/add', 'VisitorLogController@store');
Route::get('/visitorlog/show/{id}', 'VisitorLogController@show');
Route::get('/visitorlog/edit/{id}', 'VisitorLogController@edit');
Route::post('/visitorlog/update/{id}', 'VisitorLogController@update');
Route::get('/visitorlog/delete/{id}', 'VisitorLogController@destroy');
Route::get('/visitorlog/view/{id}', 'VisitorLogController@view');
Route::get('/visitorlog/print/{id}', 'VisitorLogController@print');

// call-log
Route::get('/calllog', 'CallLogController@index');
Route::get('/calllog/showlist', 'CallLogController@showlist');
Route::get('/calllog/list', 'CallLogController@list');
Route::get('/calllog/add', 'CallLogController@create');
Route::post('/calllog/add', 'CallLogController@store');
Route::get('/calllog/show/{id}', 'CallLogController@show');
Route::get('/calllog/edit/{id}', 'CallLogController@edit');
Route::post('/calllog/update/{id}', 'CallLogController@update');
Route::get('/calllog/delete/{id}', 'CallLogController@destroy');

// postal-log
Route::get('/postalrecord', 'PostalRecordController@index');
Route::get('/postalrecord/showlist', 'PostalRecordController@showlist');
Route::get('/postalrecord/list', 'PostalRecordController@list');
Route::get('/postalrecord/add', 'PostalRecordController@create');
Route::post('/postalrecord/add', 'PostalRecordController@store');
Route::get('/postalrecord/show/{id}', 'PostalRecordController@show');
Route::get('/postalrecord/edit/{id}', 'PostalRecordController@edit');
Route::post('/postalrecord/update/{id}', 'PostalRecordController@update');
Route::get('/postalrecord/delete/{id}', 'PostalRecordController@destroy');

// holiday
Route::get('/holidays/list', 'HolidaysController@list');
Route::get('/holidaylist', 'HolidaysController@index');

// noticeboard
Route::get('/notices', 'NoticeBoardController@index');
Route::get('/notice/show/list', 'NoticeBoardController@list');

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

// password and avatar

Route::get('/changepassword', 'UserProfileController@ChangePassword');
Route::post('/changepassword', 'UserProfileController@updateChangePassword');

Route::get('/changeavatar', 'UserProfileController@changeavatar');
Route::post('/changeavatar', 'UserProfileController@updatechangeavatar');
Route::get('/getavatar', 'UserProfileController@getavatar');

// notification
Route::get('/notification/list', 'NotificationController@indexList');
Route::get('/notifications', 'NotificationController@index');
Route::post('/notification/read', 'NotificationController@store');
Route::get('/notification/showList', 'NotificationController@showList');

// Telephone Directory

// index
Route::get('/phonenumbers', 'TelephoneDirectoryController@index');

// add
Route::get('/phonenumber/list', 'TelephoneDirectoryController@list');
Route::get('/phonenumber/add', 'TelephoneDirectoryController@create');
Route::post('/phonenumber/add', 'TelephoneDirectoryController@store');

// show
Route::get('/phonenumber/show/{id}', 'TelephoneDirectoryController@show');

// edit
Route::get('/phonenumber/editlist/{id}', 'TelephoneDirectoryController@editlist');
Route::get('/phonenumber/edit/{id}', 'TelephoneDirectoryController@edit');
Route::post('/phonenumber/edit/{id}', 'TelephoneDirectoryController@update');

// delete
Route::get('/phonenumber/delete/{id}', 'TelephoneDirectoryController@destroy');

// leave application
Route::get('/leave/list', 'LeaveController@list');
Route::get('/leave/pendingCount', 'LeaveController@pendingCount');
Route::get('/staff/leaves', 'LeaveController@index');
// add
Route::get('/leave/add/list', 'LeaveController@createList');
Route::get('/leave/add', 'LeaveController@create');
Route::post('/leave/add', 'LeaveController@store');
// show
Route::get('/leave/show/{id}', 'LeaveController@view');
// edit
Route::get('/leave/edit/list/{id}', 'LeaveController@show');
Route::get('/leave/edit/{id}', 'LeaveController@edit');
Route::post('/leave/edit/{id}', 'LeaveController@update');

// delete
Route::get('/leave/delete/{id}', 'LeaveController@destroy');

Route::get('/notice/list', 'NoticeBoardController@noticelist');

// class wall

// page
/*Route::get( '/classwall/page/list', 'PagesController@list' );
Route::get( '/classwall/pages', 'PagesController@index' );

Route::get( '/classwall/page/showList/{id}', 'PagesController@showList' );
Route::get( '/classwall/page/show/{id}', 'PagesController@show' );

Route::post( '/classwall/page/follow/{id}', 'PageDetailsController@follow' );
Route::post( '/classwall/page/like/{id}', 'PageDetailsController@like' );
Route::post( '/classwall/page/dislike/{id}', 'PageDetailsController@dislike' );

//post
Route::get( '/classwall/post/list', 'PostsController@indexList' );
Route::get( '/classwall/posts', 'PostsController@index' );

Route::get( '/classwall/post/showList/{id}', 'PostsController@showList' );
Route::get( '/classwall/post/show/{id}', 'PostsController@show' );
Route::get( '/classwall/post/edit/commentList/{comment_id}', 'PostsController@editCommentList' );

Route::post( '/classwall/post/like/{post_id}', 'PostDetailController@like' );
Route::post( '/classwall/post/dislike/{post_id}', 'PostDetailController@dislike' );
Route::post( '/classwall/post/save/{post_id}', 'PostDetailController@save' );
Route::post( '/classwall/post/unsave/{post_id}', 'PostDetailController@unsave' );

Route::post( '/classwall/post/add/comment/{post_id}', 'PostCommentsController@addComment' );
Route::get( '/classwall/post/edit/commentList/{comment_id}', 'PostCommentsController@editCommentList' );
Route::post( '/classwall/post/edit/comment/{comment_id}', 'PostCommentsController@editComment' );
Route::get( '/classwall/post/delete/comment/{comment_id}', 'PostCommentsController@destroy' );

Route::post( '/classwall/post/comment/like/{comment_id}', 'PostCommentDetailsController@like' );
Route::post( '/classwall/post/comment/dislike/{comment_id}', 'PostCommentDetailsController@dislike' );*/
