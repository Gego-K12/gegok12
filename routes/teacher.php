<?php

//dashboard
Route::get( '/dashboard', 'DashboardController@index' );
Route::get( '/dashboard/timetable', 'DashboardController@timetable' );
Route::get( '/dashboard/tasklist/{task_flag}','DashboardController@list' );
Route::get( '/dashboard/task/count','DashboardController@listCount' );

//calendar
Route::get( '/events','EventsController@index' );
Route::get( '/events/show', 'EventsController@events' );
Route::get( '/events/details/{id}', 'EventsController@details' );
Route::get( '/events/showdetails/{id}', 'EventsController@showdetails' );

//without approval assignment -- do not remove
/*//assignment
//index
Route::get( '/assignment/show/list', 'AssignmentController@showList' );
Route::get( '/assignments', 'AssignmentController@index' );
//add
Route::get( '/assignment/list', 'AssignmentController@list' );
Route::get( '/assignment/add', 'AssignmentController@create' );
Route::post( '/assignment/add', 'AssignmentController@store' );
//edit
Route::get( '/assignment/edit/list/{id}', 'AssignmentController@show' );
Route::get( '/assignment/edit/{id}', 'AssignmentController@edit' );
Route::post( '/assignment/edit/{id}', 'AssignmentController@update' );
//delete
Route::get( '/assignment/delete/{id}', 'AssignmentController@destroy' );*/
//without approval assignment -- do not remove

Route::group([ 'namespace' =>'Approval' ], function () {
    //with approval  
    //assignment
        //index
        Route::get( '/assignment/list/{status}', 'AssignmentController@showList' );
        Route::get( '/assignments', 'AssignmentController@index' );
        //add
        Route::get( '/assignment/add/list', 'AssignmentController@list' );
        Route::get( '/assignment/add', 'AssignmentController@create' );
        Route::post( '/assignment/add', 'AssignmentController@store' );
        //edit
        Route::get( '/assignment/edit/list/{id}', 'AssignmentController@show' );
        Route::get( '/assignment/edit/{id}', 'AssignmentController@edit' );
        Route::post( '/assignment/edit/{id}', 'AssignmentController@update' );
        //delete
        Route::get( '/assignment/delete/{id}', 'AssignmentController@destroy' );
         Route::get( '/assignment/viewers/{id}', 'AssignmentController@view' );

        //assignment-approval
        Route::group(['middleware' => ['role:principal']], function () {
            //approve
            Route::post('/assignment/approve/{id}', 'AssignmentApprovalController@approve');

            //reject
            Route::post('/assignment/reject/{id}', 'AssignmentApprovalController@reject');

            Route::post('/homework/approve/{id}', 'HomeWorkApprovalController@approve');

            //reject
            Route::post('/homework/reject/{id}', 'HomeWorkApprovalController@reject');
        });

    //with approval  
});

//student assignment
Route::get( '/assignment/show/list/{id}', 'StudentAssignmentController@showAssignmentList' );
Route::get( '/assignment/show/{id}', 'StudentAssignmentController@index' );
//add
Route::post( '/assignment/addMarks/{id}', 'StudentAssignmentController@store' );
//edit
Route::get( '/assignment/show/edit/list/{id}', 'StudentAssignmentController@show' );
Route::post( '/assignment/editMarks/{id}', 'StudentAssignmentController@update' );
//leave application
Route::get( '/leave/list', 'LeaveController@list' );
Route::get( '/leave/pendingCount', 'LeaveController@pendingCount' );
Route::get( '/leaves', 'LeaveController@index' );
//add
Route::get( '/leave/add/list', 'LeaveController@createList' );
Route::get( '/leave/add', 'LeaveController@create' );
Route::post( '/leave/add', 'LeaveController@store' );
//show
Route::get( '/leave/show/{id}', 'LeaveController@view' );
//edit
Route::get( '/leave/edit/list/{id}', 'LeaveController@show' );
Route::get( '/leave/edit/{id}', 'LeaveController@edit' );
Route::post( '/leave/edit/{id}', 'LeaveController@update' );

//delete
Route::get( '/leave/delete/{id}', 'LeaveController@destroy' );

//holiday
Route::get( '/holidays/list', 'HolidaysController@list' );
Route::get('/holidays','HolidaysController@index');

//task
    //add
    Route::get('/task/add/list','TaskController@list');
    Route::get('/tasks','TaskController@index');
    Route::get('/task/add','TaskController@create');
    Route::post('/task/add','TaskController@store');

    //index
    Route::get('/task/list', 'TaskController@showlist');
    Route::post('/task/completed','TaskController@changestatus');

    //show
    Route::get('/task/show/{id}', 'TaskController@show');
     Route::get('/task/view/{id}', 'TaskController@view');

    //edit
    Route::get('/task/edit/list/{id}', 'TaskController@editList');
    Route::get('/task/edit/{id}', 'TaskController@edit');
    Route::post('/task/edit/{id}', 'TaskController@update');

    //snooze
    Route::post('/task/snooze/{id}', 'TaskController@snooze');

    //delete
    Route::get('/task/{id}/delete', 'TaskController@destroy');

//password and avatar

Route::get( '/changepassword', 'UserProfileController@ChangePassword' );
Route::post( '/changepassword', 'UserProfileController@updateChangePassword' );

Route::get( '/changeavatar', 'UserProfileController@changeavatar' );
Route::post( '/changeavatar', 'UserProfileController@updatechangeavatar' );
Route::get( '/getavatar', 'UserProfileController@getavatar' );

//activitylog
Route::get( '/activity', 'ActivityLogController@index' );

//chat room
// Route::get( '/chats', 'ChatController@index' );
// Route::get( '/chat/{room}', 'ChatController@show' );

//Private chat room
Route::get( '/conversations', 'ConversationController@index' )->name( 'teacher.conversations.index' );
Route::get( '/conversations/create', 'ConversationController@create' )->name( 'teacher.conversations.create' );
Route::get( '/conversations/{conversation}', 'ConversationController@show' )->name( 'teacher.conversations.show' );

Route::group(['middleware' => ['role:leave_checker']], function () {
    //my leaves
    Route::get('/leave/mylist', 'LeaveController@myList');
    Route::get('/myleaves', 'LeaveController@myIndex');
    //approve
    Route::get('/leave/approve/list/{id}', 'LeaveController@approveList');
    Route::get('/leave/approve/{id}', 'LeaveController@approveCreate');
    Route::post('/leave/approve/{id}', 'LeaveController@approveStore');
    //reject
    Route::get('/leave/reject/{id}', 'LeaveController@rejectCreate');
    Route::post('/leave/reject/{id}', 'LeaveController@rejectStore');
});


//lesson-plan

//index
Route::get('/lessonplan/list/{status}', 'LessonPlanController@list');
Route::get('/lessonplans', 'LessonPlanController@index');

//show
Route::get('/lessonplan/show/{id}', 'LessonPlanController@show');

//print
Route::get('/lessonplan/print/{id}', 'LessonPlanController@print');

//delete
Route::get( '/lessonplan/delete/{id}', 'LessonPlanController@destroy');


//add
Route::get('/lessonplan/add/list', 'LessonPlanAddController@addList');
Route::get('/lessonplan/add', 'LessonPlanAddController@create');
Route::post('/lessonplan/add', 'LessonPlanAddController@store');
Route::post('/lessonplan/add/stepOne', 'LessonPlanAddController@stepOne');
Route::post('/lessonplan/add/stepTwo/{id}', 'LessonPlanAddController@stepTwo');
Route::post('/lessonplan/add/stepThree/{id}', 'LessonPlanAddController@stepThree');
Route::post('/lessonplan/add/stepFour/{id}', 'LessonPlanAddController@stepFour');
Route::post('lesson-plans/{id}/publish', 'LessonPlanAddController@publish');

//edit
Route::get('/lessonplan/edit/list/{id}', 'LessonPlanEditController@editList');
Route::get('/lessonplan/edit/{id}', 'LessonPlanEditController@edit');
Route::post('/lessonplan/edit/{id}', 'LessonPlanEditController@update');
Route::post('/lessonplan/edit/stepOne/{id}', 'LessonPlanEditController@stepOne');
Route::post('/lessonplan/edit/stepTwo/{id}', 'LessonPlanEditController@stepTwo');
Route::post('/lessonplan/edit/stepThree/{id}', 'LessonPlanEditController@stepThree');
Route::post('/lessonplan/edit/stepFour/{id}', 'LessonPlanEditController@stepFour');

//lesson-plan-approval
Route::group(['middleware' => ['role:principal']], function () {
    //approve
    Route::post('/lessonplan/approve/{id}', 'LessonPlanApprovalController@approve');

    //reject
    Route::post('/lessonplan/reject/{id}', 'LessonPlanApprovalController@reject');
});

//standardlinks
Route::get('/standardLink/list','StandardsLinkController@list');
Route::get('/standardLinks','StandardsLinkController@index');
Route::get('/standardLink/show/{id}','StandardsLinkController@show');

//show
Route::get( '/notice/show/list', 'StandardsLinkController@showNotice' );
Route::get( '/standardLink/show/timetable/{id}', 'StandardsLinkController@showTimetable' );
Route::get( '/standardLink/show/teachers/{id}', 'StandardsLinkController@showTeachers' );
Route::get( '/standardLink/show/students/{id}', 'StandardsLinkController@showStudents' );
Route::get( '/standardLink/show/events/{id}', 'StandardsLinkController@showEvents' );
Route::get( '/standardLink/show/pastExams/{id}', 'StandardsLinkController@showPastExams' );
Route::get( '/standardLink/show/fees/{id}', 'StandardsLinkController@showFees' );


Route::get( '/homework/show/list', 'StandardsLinkDetailsController@showHomework' );
Route::get( '/standardLink/show/student/attendances/{id}', 'StandardsLinkDetailsController@getStudentAttendance' );
Route::get( '/standardLink/show/attendances/{id}', 'StandardsLinkDetailsController@getAttendance' );
Route::post( '/standardLink/show/attendances/{id}', 'StandardsLinkDetailsController@showAttendance' );
Route::get( '/standardLink/show/upcomingExams/{id}', 'StandardsLinkDetailsController@showUpcomingExams' );
Route::get( '/standardLink/show/classwall/{id}', 'StandardsLinkDetailsController@showClassWall' );
Route::get( '/standardLink/show/comments/{post_id}', 'StandardsLinkDetailsController@showComments' );

//attendance
//add
Route::get( '/attendance/list', 'AttendanceController@list' );
Route::get( '/attendance/add', 'AttendanceController@create' );
Route::post( '/attendance/add', 'AttendanceController@store' );
//export
Route::get( '/attendance/export/{standardLink_id}', 'AttendanceController@export' );

//without approval homework -- do not remove
/*//homework
    //index
    Route::get( '/homework/show/list', 'HomeWorkController@showList' );
    Route::get( '/homeworks', 'HomeWorkController@index' );

    //add
    Route::get( '/homework/list', 'HomeWorkController@list' );
    Route::get( '/homework/add', 'HomeWorkController@create' );
    Route::post( '/homework/add', 'HomeWorkController@store' );

    //show
    Route::get( '/homework/show/{id}', 'HomeWorkController@show' );

    //edit
    Route::get( '/homework/edit/list/{id}', 'HomeWorkController@editList' );
    Route::get( '/homework/edit/{id}', 'HomeWorkController@edit' );
    Route::post( '/homework/edit/{id}', 'HomeWorkController@update' );

    //delete
    Route::get( '/homework/delete/{id}', 'HomeWorkController@destroy' );*/
//without approval homework -- do not remove

Route::group([ 'namespace' =>'Approval' ], function () {
    //with approval  
    //homework
        //index
        Route::get( '/homework/show/{status}/list', 'HomeWorkController@showList' );
        Route::get( '/homeworks', 'HomeWorkController@index' );

        //add
        Route::get( '/homework/list', 'HomeWorkController@list' );
        Route::get( '/homework/add', 'HomeWorkController@create' );
        Route::post( '/homework/add', 'HomeWorkController@store' );

        //show
        Route::get( '/homework/show/{id}', 'HomeWorkController@show' );

        //edit
        Route::get( '/homework/edit/list/{id}', 'HomeWorkController@editList' );
        Route::get( '/homework/edit/{id}', 'HomeWorkController@edit' );
        Route::post( '/homework/edit/{id}', 'HomeWorkController@update' );

        //delete
        Route::get( '/homework/delete/{id}', 'HomeWorkController@destroy' );
        Route::get( '/homework/viewers/{id}', 'HomeWorkController@view' );
    //with approval  
});

//student homework
    //show
    Route::get( '/studenthomeworks/{id}', 'StudentHomeworkController@list' );
    Route::get( '/studenthomework/show/{id}', 'StudentHomeworkController@show' );

    //edit
    Route::post( '/studenthomework/edit/{id}', 'StudentHomeworkController@update' );

//visitor-log
Route::get( '/visitorlog', 'VisitorLogController@index' );
Route::get('/visitorlog/showlist', 'VisitorLogController@showlist');
Route::get('/visitorlog/list', 'VisitorLogController@list');
Route::get('/visitorlog/add','VisitorLogController@create');
Route::post('/visitorlog/add','VisitorLogController@store');
Route::get('/visitorlog/show/{id}','VisitorLogController@show');
Route::get('/visitorlog/edit/{id}', 'VisitorLogController@edit');
Route::post('/visitorlog/update/{id}', 'VisitorLogController@update');
Route::get('/visitorlog/delete/{id}', 'VisitorLogController@destroy');


//call-log
Route::get( '/calllog', 'CallLogController@index' );
Route::get('/calllog/showlist', 'CallLogController@showlist');
Route::get('/calllog/list', 'CallLogController@list');
Route::get('/calllog/add','CallLogController@create');
Route::post('/calllog/add','CallLogController@store');
Route::get('/calllog/show/{id}','CallLogController@show');
Route::get('/calllog/edit/{id}', 'CallLogController@edit');
Route::post('/calllog/update/{id}', 'CallLogController@update');
Route::get('/calllog/delete/{id}', 'CallLogController@destroy');


//call-log
Route::get( '/postalrecord', 'PostalRecordController@index' );
Route::get('/postalrecord/showlist', 'PostalRecordController@showlist');
Route::get('/postalrecord/list', 'PostalRecordController@list');
Route::get('/postalrecord/add','PostalRecordController@create');
Route::post('/postalrecord/add','PostalRecordController@store');
Route::get('/postalrecord/show/{id}','PostalRecordController@show');
Route::get('/postalrecord/edit/{id}', 'PostalRecordController@edit');
Route::post('/postalrecord/update/{id}', 'PostalRecordController@update');
Route::get('/postalrecord/delete/{id}', 'PostalRecordController@destroy');

//student details
Route::get( '/student/show/{name}', 'StudentDetailsController@show' );
Route::get( '/student/show/details/{name}', 'StudentDetailsController@showDetails' );
Route::get( '/student/show/relations/{name}', 'StudentDetailsController@showRelations' );
Route::get( '/student/show/siblings/{name}', 'StudentDetailsController@showSiblings' );
Route::get( '/student/show/activity/{name}', 'StudentDetailsController@showActivity' );
Route::get( '/student/show/discipline/{name}', 'StudentDetailsController@showDisciplines' );
Route::get( '/student/show/attendance/{name}', 'StudentDetailsController@showAttendance' );
Route::get( '/student/show/libraryactivity/{name}', 'StudentDetailsController@showBookLent' );
Route::get( '/student/showmark/{name}', 'StudentDetailsController@showmark' );
Route::get( '/student/showallmark/{name}', 'StudentDetailsController@showAllMark' );
Route::get( '/student/comparemark/{name}', 'StudentDetailsController@compareMarks' );
Route::get( '/student/show/medicalHistory/{name}', 'StudentDetailsController@showMedicalHistory' );
Route::get( '/document/get/{name}', 'StudentDetailsController@showDocuments' );

//marks
// Route::get( '/marks/view/{standard_id}', 'MarkController@view' );
// Route::get( '/marks/show', 'MarkController@show' );
// Route::get( '/marks/viewmark/{standard_id}/{user_id}/{exam_id}/{academic_year_id}', 'MarkController@viewmark' );

//class wall

//page
Route::get( '/classwall/page/list', 'PagesController@list' );
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

Route::get( '/classwall/post/delete/{id}', 'PostsController@destroy' );

Route::get( '/classwall/post/add/list', 'PostAddController@createList' );
Route::get( '/classwall/post/add', 'PostAddController@create' );
Route::post( '/classwall/post/add', 'PostAddController@store' );
Route::post( '/classwall/post/add/attachment', 'PostAddController@attachment' );

Route::get( '/classwall/post/editList/{id}', 'PostEditController@editList' );
Route::get( '/classwall/post/edit/{id}', 'PostEditController@edit' );
Route::post( '/classwall/post/edit/{id}', 'PostEditController@update' );
Route::post( '/classwall/post/edit/attachment/{id}', 'PostEditController@editAttachment' );

Route::post( '/classwall/post/like/{post_id}', 'PostDetailController@like' );
Route::post( '/classwall/post/dislike/{post_id}', 'PostDetailController@dislike' );
Route::post( '/classwall/post/save/{post_id}', 'PostDetailController@save' );
Route::post( '/classwall/post/unsave/{post_id}', 'PostDetailController@unsave' );

Route::post( '/classwall/post/add/comment/{post_id}', 'PostCommentsController@addComment' );
Route::get( '/classwall/post/edit/commentList/{comment_id}', 'PostCommentsController@editCommentList' );
Route::post( '/classwall/post/edit/comment/{comment_id}', 'PostCommentsController@editComment' );
Route::get( '/classwall/post/delete/comment/{comment_id}', 'PostCommentsController@destroy' );

Route::get( '/classwall/post/replyComment/{post_comment_id}', 'PostReplyCommentsController@list' );
Route::post( '/classwall/post/reply/add/comment/{post_comment_id}', 'PostReplyCommentsController@addComment' );
Route::post( '/classwall/post/reply/edit/comment/{post_comment_id}', 'PostReplyCommentsController@editComment' );
Route::get( '/classwall/post/reply/delete/comment/{post_comment_id}', 'PostReplyCommentsController@destroy' );

Route::post( '/classwall/post/comment/like/{comment_id}', 'PostCommentDetailsController@like' );
Route::post( '/classwall/post/comment/dislike/{comment_id}', 'PostCommentDetailsController@dislike' );

Route::get('/notification/list', 'NotificationController@indexList');
Route::get('/notifications', 'NotificationController@index');
Route::post('/notification/read', 'NotificationController@store');
Route::get('/notification/showList', 'NotificationController@showList');

//student leave
Route::group(['middleware' => ['role:student_leave_checker']], function () {
    Route::get('/studentLeave/list/{status}','StudentLeaveController@indexList');
    Route::get('/studentLeaves','StudentLeaveController@index');

    //approve
    Route::get('/studentLeave/approve/list/{id}', 'StudentLeaveController@list');
    Route::get('/studentLeave/approve/{id}', 'StudentLeaveController@approveCreate');
    Route::post('/studentLeave/approve/{id}', 'StudentLeaveController@approveStore');

    //reject
    Route::get('/studentLeave/reject/{id}', 'StudentLeaveController@rejectCreate');
    Route::post('/studentLeave/reject/{id}', 'StudentLeaveController@rejectStore');
});

//noticeboard
    //index
    Route::get( '/notice/show/list', 'NoticeBoardController@list' );
    Route::get( '/notices', 'NoticeBoardController@index' );

//Feed
Route::get('/feeds', 'FeedController@index');

//library activity
Route::get( '/libraryactivity', 'LibraryActivityController@index' );
Route::get( '/libraryactivity/show', 'LibraryActivityController@show' );

Route::get( '/student/show/fees/{name}', 'StudentDetailsController@showFees' );
Route::get( '/teacher/show/leave/{name}', 'StudentDetailsController@showLeaveHistory' );

Route::get('/bankdetails/get/{name}', 'BankDetailController@index');
Route::post('/bankdetails/add/{name}', 'BankDetailController@store');
Route::get('/bankdetails/edit/{id}', 'BankDetailController@edit');
Route::post('/bankdetails/update/{id}', 'BankDetailController@update');

Route::get( '/notice/list', 'NoticeBoardController@noticelist' );

// Group
Route::get('groups/{standardLinkId}', 'GroupController@index');


