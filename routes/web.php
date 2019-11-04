<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'isAvailable'], static function () {
    Route::get('/', 'PublicUsage@getWeekMenu');
    Route::get('/{day}', 'PublicUsage@getDayMenu');
    Route::get('/{day}/{time}', 'PublicUsage@getDayTimeMenu');
});

// System route
Route::post('/', 'SystemUsage@SystemRun');
