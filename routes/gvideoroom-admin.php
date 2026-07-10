<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Videoroom\Http\Controllers\Admin\VideoConferencesController;
use Gegok12\Videoroom\Http\Controllers\Admin\Media\MediaImageController;
use Gegok12\Videoroom\Http\Controllers\Admin\Media\MediaVideoController;
use Gegok12\Videoroom\Http\Controllers\Admin\Media\MediaAudioController;
use Gegok12\Videoroom\Http\Controllers\Admin\VideosController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php.

/*video conference*/
Route::get('/video-conference', [VideoConferencesController::class,'index']);
Route::get('/video-conference/create', [VideoConferencesController::class,'create']);
Route::post('/video-conference/save', [VideoConferencesController::class,'store']);
Route::get('/video-conference/{slug}', [VideoConferencesController::class,'show']);
Route::get('/video-conference/edit/{id}', [VideoConferencesController::class,'edit']);
Route::post('/video-conference/update/{id}', [VideoConferencesController::class,'update']);
Route::get('/video-conference/remove/{id}', [VideoConferencesController::class,'remove']);
Route::get('/video-conference/manage-invites/{id}', [VideoConferencesController::class,'invites']);
Route::get('/video-conference/remove-users/{id}', [VideoConferencesController::class,'removeUsers']);
Route::get('/video-conference/status/{id}', [VideoConferencesController::class,'statusUpdate']);
Route::get('/video-conference/recordings/{id}', [VideoConferencesController::class,'recordings']);
Route::get('/video-conference/add-invites/{id}', [VideoConferencesController::class,'addinvites']);
Route::post('/video-conference/save-invites/{id}', [VideoConferencesController::class,'saveinvites']);
Route::get('/video-conference/editlist/{id}', [VideoConferencesController::class,'editlist']);
Route::get('/video-conference/show/{id}', [VideoConferencesController::class,'showduration']);

//videos
  //index
  Route::get( '/videos/list', [VideosController::class,'standardlist']);
  Route::get( '/files', [VideosController::class,'index']);
  Route::get( '/file/list/{type}', [VideosController::class,'list']);

  //add
  Route::get( '/file/add', [VideosController::class,'create']);
  Route::post( '/file/add', [VideosController::class,'store']);
  Route::post( '/storevideos', [VideosController::class,'videostore']);
  Route::post( '/storeimage', [VideosController::class,'storeimage']);
  Route::post( '/sessionsave', [VideosController::class,'save']);

  //show
  Route::get( '/file/show/{id}', [VideosController::class,'show']);

  Route::get( '/video/show', [VideosController::class,'view']);

  //edit
  Route::get( '/file/edit/{id}', [VideosController::class,'edit']);
  Route::post( '/file/edit/{id}', [VideosController::class,'update']);
  Route::get( '/videos/download/{id}', [VideosController::class,'downloadattachments']);

  //delete
  Route::get( '/file/delete/{id}', [VideosController::class,'destroy']);
  Route::get( '/file/viewers/{id}', [VideosController::class,'viewers']);

  //media files-image
  Route::post( '/image/validation', [MediaImageController::class,'validation']);
  Route::post( '/media/storeimage', [MediaImageController::class,'store']);
  Route::post( '/media/storevideos', [MediaVideoController::class,'videostore']);
  Route::post( '/media/video/add', [MediaVideoController::class,'store']);
  Route::post( '/media/storeaudios', [MediaAudioController::class,'audiostore']);
  Route::post( '/media/audio/add', [MediaAudioController::class,'store']);
  Route::post( '/media/audio/record', [MediaAudioController::class,'recordings']);
