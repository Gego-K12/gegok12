<?php

//dashboard
Route::get( '/dashboard', 'DashboardController@index' );
Route::get('/dashboard/tasklist/{task_flag}','DashboardController@list');
Route::get( '/dashboard/task/count','DashboardController@listCount' );

//calendar
Route::get('/events','EventsController@index');
Route::get( '/events/show', 'EventsController@events' );
Route::get( '/events/details/{id}', 'EventsController@details' );
Route::get( '/events/showdetails/{id}', 'EventsController@showdetails' );

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

    //edit
    Route::get('/task/edit/list/{id}', 'TaskController@editList');
    Route::get('/task/edit/{id}', 'TaskController@edit');
    Route::post('/task/edit/{id}', 'TaskController@update');

    //snooze
    Route::post('/task/snooze/{id}', 'TaskController@snooze');

    //delete
    Route::get('/task/{id}/delete', 'TaskController@destroy');

    // routes/web.php — alongside your other task routes
    Route::post('/task/claim/{id}', 'TaskController@claim');

//assignment
//index
Route::get( '/assignment/show/list', 'AssignmentController@showList' );
Route::get( '/assignments', 'AssignmentController@index' );
//add
Route::post( '/assignment/add', 'AssignmentController@store' );
//show
Route::get( '/assignment/show/edit/list/{id}', 'AssignmentController@show' );
//delete
Route::get( '/assignment/delete/{id}', 'AssignmentController@destroy' );

//homework
//index
Route::get( '/homework/show/list', 'HomeworkController@showList' );
Route::get( '/homeworks', 'HomeworkController@index' );
//add
Route::get( '/homework/list', 'HomeworkController@list' );
Route::get( '/homework/add', 'HomeworkController@create' );
Route::post( '/homework/add/{id}', 'HomeworkController@store' );
Route::post( '/homework/reply/{id}', 'HomeworkController@reply' );
//show
Route::get( '/homework/show/{id}', 'HomeworkController@show' );
//delete
Route::get( '/homework/delete/{id}', 'HomeworkController@destroy' );

//password and avatar
Route::get( '/changepassword', 'UserProfileController@ChangePassword' );
Route::post( '/changepassword', 'UserProfileController@updateChangePassword' );
//holiday
Route::get( '/holidays/list', 'HolidaysController@list' );
Route::get('/holidays','HolidaysController@index');
//activitylog
Route::get( '/activity', 'ActivityLogController@index' );
//library activity
Route::get( '/libraryactivity', 'LibraryActivityController@index' );
Route::get( '/libraryactivity/show', 'LibraryActivityController@show' );
//chat room
// Route::get( '/chats', 'ChatController@index' );
// Route::get( '/chat/{room}', 'ChatController@show' );
//Private chat room
Route::get( '/conversations', 'ConversationController@index' )->name( 'student.conversations.index' );
Route::get( '/conversations/create', 'ConversationController@create' )->name( 'student.conversations.create' );
Route::get( '/conversations/{conversation}', 'ConversationController@show' )->name( 'student.conversations.show' );





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

Route::post( '/classwall/post/like/{post_id}', 'PostDetailController@like' );
Route::post( '/classwall/post/dislike/{post_id}', 'PostDetailController@dislike' );
Route::post( '/classwall/post/save/{post_id}', 'PostDetailController@save' );
Route::post( '/classwall/post/unsave/{post_id}', 'PostDetailController@unsave' );

Route::post( '/classwall/post/add/comment/{post_id}', 'PostCommentsController@addComment' );
Route::get( '/classwall/post/edit/commentList/{comment_id}', 'PostCommentsController@editCommentList' );
Route::post( '/classwall/post/edit/comment/{comment_id}', 'PostCommentsController@editComment' );
Route::get( '/classwall/post/delete/comment/{comment_id}', 'PostCommentsController@destroy' );

Route::get( '/classwall/post/replyComment/{post_comment_id}', 'PostReplyCommentsController@list' );
      Route::post( '/classwall/post/reply/add/comment/{post_comment_id}', 'PostReplyCommentsController@addComment');
      Route::post( '/classwall/post/reply/edit/comment/{post_comment_id}', 'PostReplyCommentsController@editComment');
      Route::get( '/classwall/post/reply/delete/comment/{post_comment_id}', 'PostReplyCommentsController@destroy');

Route::post( '/classwall/post/comment/like/{comment_id}', 'PostCommentDetailsController@like' );
Route::post( '/classwall/post/comment/dislike/{comment_id}', 'PostCommentDetailsController@dislike' );

Route::get('/notification/list', 'NotificationController@indexList');
Route::get('/notifications', 'NotificationController@index');
Route::post('/notification/read', 'NotificationController@store');
Route::get('/notification/showList', 'NotificationController@showList');

//Feed
Route::get('/feeds', 'FeedController@index');

//noticeboard
    //index
    Route::get( '/notice/show/list', 'NoticeBoardController@list' );
    Route::get( '/notices', 'NoticeBoardController@index' );