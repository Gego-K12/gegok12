<?php
if (class_exists(\App\Http\Controllers\Admin\Addon\AddonInstallExamController::class)) {
Route::get('/addon/install/exam', 'Addon\AddonInstallExamController@create');
Route::post('/addon/install/exam', 'Addon\AddonInstallExamController@store');

}
