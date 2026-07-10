<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Chat\Http\Controllers\Admin\ChatController;
use Gegok12\Chat\Http\Controllers\Admin\RoomLinksController;
use Gegok12\Chat\Http\Controllers\Admin\ConversationController;

// Required from the end of routes/admin.php, so these inherit the admin
// portal's prefix('admin') + middleware(['web','auth','schooladmin','privilegeconditions'])
// group set up in app/Providers/RouteServiceProvider.php. Using
// [Controller::class, 'method'] array syntax (rather than bare
// 'Controller@method' strings) bypasses that group's
// ->namespace('App\Http\Controllers\Admin') setting, so the controller can
// stay in the package's own namespace.

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/chat/{room}', [ChatController::class, 'show'])->name('chat.room');
Route::get('/chat/room/add', [ChatController::class, 'create']);
Route::post('/chat/room/add', [ChatController::class, 'store']);
Route::get('/room/showDetails/{id}', [ChatController::class, 'showDetails']);
Route::get('/room/edit/{id}', [ChatController::class, 'edit']);
Route::post('/room/update/{id}', [ChatController::class, 'update']);
Route::get('/room/delete/{id}', [ChatController::class, 'destroy']);

// Chat Room
Route::get('/room/list/{room_id}', [RoomLinksController::class, 'list']);
Route::get('/room/student/{room_id}/{standardLink_id}/list', [RoomLinksController::class, 'studentlist']);
Route::get('/room/addMember/{room_id}', [RoomLinksController::class, 'create']);
Route::post('/room/addMember/{room_id}', [RoomLinksController::class, 'store']);
Route::get('/room/removeMember/{id}', [RoomLinksController::class, 'destroy']);

// Private chat room
Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
Route::get('/conversations/create', [ConversationController::class, 'create'])->name('conversations.create');
Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
