<?php

@include('teacherapi.php');

Route::post('/parent/login', 'Api\TokenController@issueToken');

Route::post('/logout/devices', 'Api\LoginController@logoutDevices');

//Route::get('/search/users','Api\Search\UserSearchController@index'); --test route hidden

Route::get('/schools/list','Api\SchoolController@list');

Route::get('/apk/parent','Api\ApkController@parentApp');

Route::get('/apk/teacher','Api\ApkController@teacherApp');

//password reset

Route::post('/password/reset', 'Api\UserController@resetPassword');

Route::post('/password/store', 'Api\UserController@storePassword');

Route::post('/reset/check', 'Api\UserController@checkReset');

Route::post('/reset/change/password', 'Api\UserController@resetChangePassword');

Route::get('/school/info', 'Api\SchoolController@schooldetail');

Route::group([
	'prefix' => 'v2', 
	'namespace' =>'Api' ,
    
	'middleware' => ['auth:sanctum'],
], function () {

    //Logout

    //Route::post('/logout/devices', 'LoginController@logoutDevices');
    
    Route::post('/logout', 'LoginController@logout');

    //me

    Route::get('/myinfo', 'MeController@myInfo');

    //update token

    Route::get('/updatetoken', 'UserController@updatetoken');

    //change password

    Route::post('/password/change', 'UserController@changePassword');

    //children

    Route::get('/my-children', 'ChildrenController@listChildren');

    Route::get('/my-children/count', 'ChildrenController@countChildren');

    Route::get('/my-children/{id}/details', 'ChildrenController@showChildren');

	//school details

	Route::get('/school/details', 'SchoolController@index');
     
    //Holiday

    Route::get('/holiday/list','EventsController@holidaylist');

    //magazine

    Route::get('/my-school/magazine', 'BulletinsController@show');

    //Events

    Route::get('/my-school/list-events', 'EventsController@index');

	Route::get('/my-events/upcoming', 'EventsController@upcoming');//upcoming events

    Route::get('/my-events/past', 'EventsController@showpast');//past events

    Route::get('/my-events/school', 'EventsController@school');//school events

    Route::get('/my-events/{student_id}/class', 'EventsController@class');//class events

    Route::get('/my-events/show/{id}', 'EventsController@show');

    Route::get('/my-events/gallery/show/{event_id}', 'EventGalleryController@showimage');


    //Leave

    Route::get('/leaves/{student_id}','LeaveController@index');

    Route::get('/leave/list','LeaveController@create');

    Route::post('/leave/add/{student_id}','LeaveController@store'); 

    Route::get('/leave/show/{id}','LeaveController@show');  

    Route::post('/leave/edit/{id}','LeaveController@update');  

    Route::get('/leave/delete/{id}','LeaveController@destroy'); 

    //messages

    Route::get('/messages','FeedbackController@sentMessages');

    
    Route::get('/notifications/{studentid}','FeedbackController@notifications');


    Route::post('/message/read/{id}','FeedbackController@readMessage');

	//feedbacks

	Route::get('/feedbacks','FeedbackController@index');

    Route::get('/feedback/category/list','FeedbackController@list');

	Route::post('/feedback/send/{student_id}','FeedbackController@store');
    
    //Route::post('/feedback/save/{feedbackid}','FeedbackController@conversationsave');	

    //Discipline

    Route::get('/disciplines/{student_id}','DisciplineController@index');

    Route::get('/discipline/show/{id}','DisciplineController@show');

    Route::get('/performance/{student_id}','DisciplineController@performance');

    //Homework

    Route::get('/homeworks/pending/{student_id}','HomeworkController@pending');

    Route::get('/homeworks/finished/{student_id}','HomeworkController@finished');

    Route::get('/homework/show/{student_id}/{id}','HomeworkController@show');

    Route::post('/homework/submit/{homework_id}/{student_id}','HomeworkController@store');

    Route::delete('/homework/delete/{id}/{student_id}','HomeworkController@destroy');

    Route::post('/homework/reply/{homework_id}/{student_id}','HomeworkController@replycomment');

    //Timetable

    // Route::get('/timetable/{student_id}','TimetableController@index');

    //LessonPlan

    Route::get('/lessonplan/{student_id}','LessonPlanController@index');
    
     Route::get('/lessonplan/print/{id}','LessonPlanController@print');

    Route::get('/lessonplan/{student_id}/{subject_id}','LessonPlanController@subjectIndex');

   

    //Fees

    // Route::get('/fees/paid/{student_id}','FeesController@paid');

    // Route::get('/fees/unpaid/{student_id}','FeesController@unpaid');

    // Route::get('/fees/show/{id}','FeesController@show');

    //Notice

    Route::get('/my-school/notices','NoticeBoardController@indexSchool');

    Route::get('/my-school/notices/expired','NoticeBoardController@expiredSchool');

    Route::get('/notices/{student_id}','NoticeBoardController@indexClass');

    Route::get('/notices/expired/{student_id}','NoticeBoardController@expiredClass');

    Route::get('/notice/show/{id}','NoticeBoardController@show');


    //Attendance

    Route::get('/attendance/{student_id}','AttendanceController@index');

    //Exam

    // Route::get('/exams/upcoming/{student_id}','ExamController@upcomingExam');

    // Route::get('/exams/past/{student_id}','ExamController@pastExam');

    //Mark

    // Route::get('/marks/{student_id}/{exam_id}','MarksController@index');
    // Route::get('/marks/graph/{student_id}/{exam_id}','MarksController@getmarks');

    // Route::get('/mark/show/{mark_id}','MarksController@show');

    //Teacher

    Route::get('/teachers/{student_id}','TeacherController@index');

    //Assignment

    Route::get('/assignments/{student_id}','AssignmentController@index');

    Route::get('/assignments/completed/{student_id}','AssignmentController@completed');

    Route::get('/assignment/show/{student_id}/{id}','AssignmentController@show'); 

    Route::post('/assignment/submit/{assignment_id}/{student_id}','AssignmentController@store');

    Route::delete('/assignment/delete/{id}/{student_id}','AssignmentController@destroy');  




    //task

        //index
        Route::get('/mytasks/active/{student_id}','TaskController@myActiveList');
        Route::get('/mytasks/completed/{student_id}','TaskController@myCompletedList');
        Route::get('/tasks/active/{student_id}','TaskController@activeList');
        Route::get('/tasks/completed/{student_id}','TaskController@completedList');

        //mark complete
        Route::post('/tasks/mark/complete','TaskController@changestatus'); 

        //add
        Route::get('/task/add/list','TaskController@create');  
        Route::post('/task/add/{student_id}','TaskController@store'); 

        //show
        Route::get('/task/show/{id}','TaskController@show');  

        //edit
        Route::get('/task/edit/{id}','TaskController@edit');  
        Route::post('/task/edit/{id}/{student_id}','TaskController@update');

        //snooze
        Route::post('/task/snooze/{id}/{student_id}', 'TaskController@snooze');  

        //delete
        Route::get('/task/delete/{id}','TaskController@destroy'); 
        
    //viewers details
        
    Route::post('/student/modules','StudentHistoryController@update');

    Route::get('/addons','PurchaseHistoryController@index');

    

});

Route::get('/bloodGroup/list','Api\TestController@getBloodGroup');

Route::get('/get/country','Api\UserprofileController@country');

Route::get('/get/state/{id}','Api\UserprofileController@state');

Route::get('/get/city/{id}','Api\UserprofileController@city');

Route::get('/events/show/details/{id}','Api\EventsController@showdetails');

//Testing Purpose start

//Route::get('/users', 'Api\TestController@index');

//Route::get('/teachers', 'Api\TestController@teachers');

//Route::get('/parents', 'Api\TestController@parents');

//Route::get('/events','Api\TestController@events');



