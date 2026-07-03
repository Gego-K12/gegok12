<?php

Route::get('/addon/install/exam', 'Addon\AddonInstallExamController@create');
Route::post('/addon/install/exam', 'Addon\AddonInstallExamController@store');
