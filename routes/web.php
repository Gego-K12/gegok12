<?php

use App\Models\Plugin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
  return redirect()->route('login');
    //return view('welcome');
});*/
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Impersonate as teacher
Route::get('/teacher/{id}/impersonate', 'Auth\ImpersonateController@impersonate')->middleware('auth', 'schooladmin');
Route::get('/library/{id}/impersonate', 'Auth\ImpersonateController@librarianimpersonate')->middleware('auth', 'schooladmin');
Route::get('/student/{id}/impersonate', 'Auth\ImpersonateController@studentimpersonate')->middleware('auth', 'schooladmin');
Route::get('/teacher/impersonate/stop', 'Auth\ImpersonateController@stopImpersonate');

Route::get('/schooladmin/{id}/impersonate', 'Auth\ImpersonateController@schoolAdminimpersonate')->middleware('auth', 'superadmin');

// Email Verification for Member
Route::get('/emailverification/{token}', 'Auth\EmailVerificationController@emailverification');
// OTP Verification
Route::get('/checksms', 'TestController@checksms');
Route::get('/verifyotp', 'OTPController@create');
Route::post('/verifyotp', 'OTPController@store');
// siteadmin
Route::group(['middleware' => ['siteadmin'], 'namespace' => 'Admin'], function () {
    Route::get('/payment/subscription', 'PaymentController@Subscription');
});

Route::group(['middleware' => ['auth', 'siteadmin']], function () {
    Route::get('/plugins', function () {
        return view('siteadmin.plugins.index');
    });

    Route::get('/plugins/stage', function () {
        return view('siteadmin.plugins.stage');
    });

    Route::get('/plugins/{plugin}/log', function (Plugin $plugin) {
        return view('siteadmin.plugins.log', ['plugin' => $plugin]);
    });
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
});

Route::get('/{slug}/admission-form', 'AdmissionController@create');



if (file_exists(base_path('routes/gfee.php'))) {
    require base_path('routes/gfee.php');
}

// if (file_exists(base_path('routes/gtimetable.php'))) {
//     require base_path('routes/gtimetable.php');
// }
