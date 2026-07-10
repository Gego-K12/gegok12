<?php
use Illuminate\Support\Facades\Route;
use Gegok12\Videoroom\Http\Controllers\Admin\VideoConferencesController;

// Required from the end of the host's routes/web.php, which
// RouteServiceProvider only wraps in middleware('web') — no auth, no
// prefix. This is a public GET webhook callback (Twilio's video
// composition status callback), so it belongs here rather than under
// any authenticated portal.
Route::get('/video-conference/call-back', [VideoConferencesController::class, 'callback']);
