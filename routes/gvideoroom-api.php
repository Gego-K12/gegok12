 <?php
 use Illuminate\Support\Facades\Route;
 use Gegok12\Videoroom\Http\Controllers\Api\VideoConferenceController;

//teacher
 use Gegok12\Videoroom\Http\Controllers\Api\Teacher\VideoConferenceController as TeacherVideoConferencesController;
 use Gegok12\Videoroom\Http\Controllers\Api\Teacher\MyVideoConferenceController;

  Route::group([
    'prefix' => 'v2', 
    'namespace' =>'Api' ,
    'middleware' => ['auth:sanctum'],
], function () {
    //video conference

    Route::get('/video-conference/{student_id}',[VideoConferenceController::class,'index']);

    Route::get('/video-conference/{slug}/{student_id}',[VideoConferenceController::class,'show']);
});


//teacher
  
Route::group([ 'prefix' => 'teacher' , 'middleware'=>['auth:sanctum']  ], 
    function() 
{
 //video conference

        //index
        Route::get('/video-conference',[TeacherVideoConferencesController::class,'index']);
        Route::get('/video-conference/classlist',[TeacherVideoConferencesController::class,'classlist']);

        //show
        Route::get('/video-conference/{slug}/{teacher_id}',[TeacherVideoConferencesController::class,'show']);

    //my video conference
        //add
        Route::get('/videoroom/list/standard',[MyVideoConferenceController::class,'getStandardLink']);
        Route::get('/videoroom/list/students/{standardLink_id}',[MyVideoConferenceController::class,'getStudents']);
        Route::get('/videoroom/list/subjects/{standardLink_id}',[MyVideoConferenceController::class,'subjectInfo']);
        Route::post('/videoroom/create',[MyVideoConferenceController::class,'store']);

        //show
        Route::get('/videoroom/show/{slug}',[MyVideoConferenceController::class,'show']);

        //edit
        Route::get('/videoroom/edit/list/{id}',[MyVideoConferenceController::class,'edit']);
        Route::post('/videoroom/edit/{id}',[MyVideoConferenceController::class,'update']);

        //delete
        Route::get('/videoroom/delete/{id}',[MyVideoConferenceController::class,'destroy']);

        //invites list
        Route::get('/videoroom/invites/{id}',[MyVideoConferenceController::class,'invites']);

        //remove invite
        Route::get('/videoroom/removeUser/{id}',[MyVideoConferenceController::class,'removeUsers']);

        //add invites
        Route::post('/videoroom/add/invites/{id}',[MyVideoConferenceController::class,'saveinvites']);

        //status update
        Route::get('/videoroom/status/update/{id}',[MyVideoConferenceController::class,'statusUpdate']);
    });
