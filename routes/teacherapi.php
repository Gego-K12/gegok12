<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' =>'Api\Teacher'], 
    function() 
{

    Route::get('/ugDegree/list', 'SandboxController@getUGDegree');
    Route::get('/pgDegree/list', 'SandboxController@getPGDegree');
    Route::get('/certificate/list', 'SandboxController@getCertificate');
    Route::get('/martialStatus/list', 'SandboxController@getMaritalStatus');
    Route::get('/designation/list', 'SandboxController@getDesignation');

    Route::post('/teacher/login', 'LoginController@login');
    Route::post('/teacher/logout/devices', 'LoginController@logoutDevices');
});

Route::group([ 'prefix' => 'teacher' , 'middleware'=>['auth:sanctum'] , 'namespace' =>'Api\Teacher' ], 
    function() 
{
    //Logout
    
    Route::post('/logout', 'LoginController@logout');
    //Route::post('/logout/devices', 'LoginController@logoutDevices');

    //me
    
    Route::get('/myinfo', 'MeController@myInfo');
    Route::get('/subject', 'DashboardController@index');

    //change password

    Route::post('/change/password', 'MeController@changePassword');

 

    //without approval//
    /*//assignment

        //index
        Route::get('/assignments', 'AssignmentController@assignment');
        Route::get('/assignments/completed', 'AssignmentController@completedAssignment');

        //add
        Route::get('/assignment/standardLinklist', 'AssignmentController@standardLinkList');
        Route::get('/assignment/subjectlist/{standardLink_id}', 'AssignmentController@subjectList');
        Route::post('/assignment/add', 'AssignmentController@store');

        //show
        Route::get('/assignment/show/{id}', 'AssignmentController@show');

        //edit
        Route::post('/assignment/edit/{id}', 'AssignmentController@update');

        //delete
        Route::get('/assignment/delete/{id}', 'AssignmentController@destroy');*/
    //without approval//

    Route::group([ 'namespace' =>'Approval' ], function () {
    //with approval    
        //assignment
            //index
            Route::get('/assignments', 'AssignmentController@assignment');
            Route::get('/assignments/completed', 'AssignmentController@completedAssignment');

            //add
            Route::get('/assignment/standardLinklist', 'AssignmentController@standardLinkList');
            Route::get('/assignment/subjectlist/{standardLink_id}', 'AssignmentController@subjectList');
            Route::post('/assignment/add', 'AssignmentController@store');

            //show
            Route::get('/assignment/show/{id}', 'AssignmentController@show');

            //edit
            Route::post('/assignment/edit/{id}', 'AssignmentController@update');

            //delete
            Route::delete('/assignment/delete/{id}', 'AssignmentController@destroy');

        //homework
            //index
            Route::get('/homeworks/pending','HomeworkController@pendingList');

            Route::get('/homeworks/pendingApproval','HomeworkController@pendingApprovalList');

            Route::get('/homeworks/rejectedApproval','HomeworkController@rejectedApprovalList');

            Route::get('/homeworks/completed','HomeworkController@completedList');

            //add
            Route::get('/homework/add/list','HomeworkController@create');
            Route::get('/homework/subjectlist/{standardLink_id}', 'HomeworkController@subjectList');

            Route::post('/homework/add','HomeworkController@store');

            //show
            Route::get('/homework/show/{id}','HomeworkController@show');

            //edit
            Route::get('/homework/edit/{id}','HomeworkController@edit');
            Route::post('/homework/edit/{id}','HomeworkController@update');

            //delete
            Route::delete('/homework/delete/{id}','HomeworkController@destroy');
    //with approval
    }); 

    //student assignment

        //index
        Route::get('/assignment/show/{assignment_id}/submitted', 'StudentAssignmentController@submittedAssignmentList');
        Route::get('/assignment/show/{assignment_id}/completed', 'StudentAssignmentController@completedAssignmentList');

        //add
        Route::post('/assignment/addMarks/{id}', 'StudentAssignmentController@store');

        //edit
        Route::get('/assignment/showMarks/{id}', 'StudentAssignmentController@show');
        Route::post('/assignment/editMarks/{id}', 'StudentAssignmentController@update');

    //leave application

        //index
        Route::get('/myleaves','LeaveController@index');

        //available leaves
        Route::get('/myleaves/available','LeaveController@availableList');

        //add
        Route::get('/leave/add/list','LeaveController@create');
        Route::post('/leave/add','LeaveController@store');

        //show
        Route::get('/leave/show/{id}','LeaveController@show');

        //edit
        Route::post('/leave/edit/{id}','LeaveController@update');

        //delete
        Route::get('/leave/delete/{id}','LeaveController@destroy');

    //lesson plan

        //index
        Route::get('/lessonplans','LessonPlanController@index');

        //show
        Route::get('/lessonplan/print/{id}','LessonPlanController@print');

    //task

        //index
        Route::get('/mytasks/active','TaskController@myActiveList');
        Route::get('/mytasks/completed','TaskController@myCompletedList');
        Route::get('/tasks/active','TaskController@activeList');
        Route::get('/tasks/completed','TaskController@completedList');

        //mark complete
        Route::post('/tasks/mark/complete','TaskController@changestatus'); 

        //add
        Route::get('/task/add/list','TaskController@create'); 
        Route::get('/task/add/teacher/list','TaskController@teacherList'); 
        Route::get('/task/add/student/{standardlink_id}','TaskController@studentList'); 
        Route::post('/task/add','TaskController@store'); 

        //show
        Route::get('/task/show/{id}','TaskController@show');  

        //edit
        Route::get('/task/edit/{id}','TaskController@edit');  
        Route::post('/task/edit/{id}','TaskController@update'); 
        
        //snooze
        Route::post('/task/snooze/{id}', 'TaskController@snooze'); 

        //delete
        Route::get('/task/delete/{id}','TaskController@destroy'); 

    //school details

    Route::get('/school/details', 'SchoolController@index');
     
    //holidays

    Route::get('/holidays','HolidaysController@index');
     
   
    //attendance

        //index
        Route::get('/attendance/list','AttendanceController@index');
        
        //add
        Route::post('/attendance/add','AttendanceController@store');

        //absent list
        Route::get('/attendance/absentList/{standardLink_id}/{date}/{session}','AttendanceController@absentList');

    //notice board
        Route::get('/my-school/notices','NoticeBoardController@indexSchool');

        Route::get('/notices/class/{teacher_id}','NoticeBoardController@showNotices');

        Route::get('/my-school/notices/expired','NoticeBoardController@expiredSchool');

        Route::get('/notice/show/{id}','NoticeBoardController@show');

    //without approval//
    /*//homework
        //index
        Route::get('/homeworks/pending','HomeworkController@pendingList');

        Route::get('/homeworks/completed','HomeworkController@completedList');

        //add
        Route::get('/homework/add/list','HomeworkController@create');

        Route::post('/homework/add','HomeworkController@store');

        //show
        Route::get('/homework/show/{id}','HomeworkController@show');

        //edit
        Route::get('/homework/edit/{id}','HomeworkController@edit');
        Route::post('/homework/edit/{id}','HomeworkController@update');

        //delete
        Route::get('/homework/delete/{id}','HomeworkController@destroy');*/
    //without approval//

    //student homework
        //show
        Route::get('/student/homework/show/{id}','StudentHomeworkController@show');

        //update
        Route::post('/student/homework/edit/{id}','StudentHomeworkController@update');

    //messages     
        Route::get('/notifications/{teacher_id}','FeedbackController@notifications');

        Route::get('/messages','FeedbackController@sentMessages');


    //Events  
        Route::get('/my-school/list-events', 'EventsController@index');

        Route::get('/my-events/upcoming', 'EventsController@upcoming');//upcoming events

        Route::get('/my-events/past', 'EventsController@showpast');//past events

        Route::get('/my-events/school', 'EventsController@school');//school events

        Route::get('/my-events/{teacher_id}/class', 'EventsController@class');//class events

        Route::get('/my-events/show/{id}', 'EventsController@show');

        Route::get('/my-events/gallery/show/{event_id}', 'EventGalleryController@showimage');

        Route::get('/holiday/list','EventsController@holidaylist');

      //Discipline
        Route::get('/performance/list','DisciplineController@index');
        Route::get('/performance/add/type','DisciplineController@type');
        Route::get('/performance/add/media_type','DisciplineController@media_type');
        Route::post('/performance/add','DisciplineController@store');
        Route::get('/performance/edit/{performance_id}','DisciplineController@editlist');
        Route::post('/performance/update/{performance_id}','DisciplineController@update');
        Route::get('/performance/delete/{performance_id}','DisciplineController@destroy');

  
        
});

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','role:leave_checker'] , 'namespace' =>'Api\Teacher' ], function() {

    //leave application

        //list
        Route::get('/checkleave/list','LeaveController@leaveCheckList');
        Route::get('/checkleave/approve/list','LeaveController@leaveCheckListApproved');
        Route::get('/checkleave/reject/list','LeaveController@leaveCheckListRejected');

        //approve
        Route::post('/checkleave/approve/{id}','LeaveController@approveStore');

        //reject
        Route::post('/checkleave/reject/{id}','LeaveController@rejectStore');
});

Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','role:student_leave_checker'] , 'namespace' =>'Api\Teacher' ], function() {

    //leave application

        //leave list
        Route::get('/student/leave','StudentLeaveController@indexList');

        //pending
        Route::get('/student/leave/{pending}','StudentLeaveController@index');

        //approve
        Route::post('/student/leave/approve/{leave_id}','StudentLeaveController@approveStore');

        //reject
        Route::post('/student/leave/reject/{leave_id}','StudentLeaveController@rejectStore');
});

//assignment-approval
Route::group(['prefix' => 'teacher' , 'middleware' => ['auth:sanctum','role:principal'] , 'namespace' =>'Api\Teacher\Approval' ], function () {
    //approve
    Route::post('/assignment/approve/{id}', 'AssignmentApprovalController@approve');

    //reject
    Route::post('/assignment/reject/{id}', 'AssignmentApprovalController@reject');
});

