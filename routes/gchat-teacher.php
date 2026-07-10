<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Chat\Http\Controllers\Teacher\ChatController;

// Required from the end of routes/teacher.php, inheriting the teacher
// portal's prefix('teacher') + middleware(['web','auth','teacher']) group
// from app/Providers/RouteServiceProvider.php. This plugin also requires
// 'privilegeconditions' (not part of the base teacher group), added here
// via a middleware-only nested group — no prefix, so it doesn't stack on
// top of the outer 'teacher' prefix.
Route::middleware(['privilegeconditions'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chat/{room}', [ChatController::class, 'show']);
});
