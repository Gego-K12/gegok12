<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Chat\Http\Controllers\Student\ChatController;

// Required from the end of routes/student.php, inheriting the student
// portal's prefix('student') + middleware(['web','auth','student']) group
// from app/Providers/RouteServiceProvider.php. This plugin also requires
// 'privilegeconditions' (not part of the base student group), added here
// via a middleware-only nested group — no prefix, so it doesn't stack on
// top of the outer 'student' prefix.
Route::middleware(['privilegeconditions'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chat/{room}', [ChatController::class, 'show']);
});
