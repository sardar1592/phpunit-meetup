<?php

use App\Http\Controllers\LoyaltyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/loyalty/{user}', LoyaltyController::class, '__invoke')->name('loyalty')->middleware('auth:sanctum');
