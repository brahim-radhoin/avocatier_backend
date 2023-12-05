<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvoAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EmailVerificationController;



// Route::post('login', [AuthController::class, 'login']);



// ################## Clients Routes ##########################

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api'], 'prefix' => 'user'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

// ################## Avocat Routes ##########################

Route::middleware('auth:avo')->get('/avo', function (Request $request) {
    return $request->user();
})->middleware('verify.api');

Route::apiResource('avos', AvoController::class);

Route::group(['middleware' => ['api'], 'prefix' => 'avo'], function () {
    Route::post('register', [AvoAuthController::class, 'register']);
    // Route::post('login', [AvoAuthController::class, 'login']);
    Route::post('logout', [AvoAuthController::class, 'logout']);
    Route::post('refresh', [AvoAuthController::class, 'refresh'])->middleware('verify.api');
    Route::post('me', [AvoAuthController::class, 'me'])->middleware('verify.api');
});

// ################## Admin Routes ##########################

Route::middleware('auth:admin')->get('/admin', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api'], 'prefix' => 'admin'], function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    // Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
    Route::post('refresh', [AdminAuthController::class, 'refresh']);
    Route::post('me', [AdminAuthController::class, 'me']);
});


Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
->middleware(['auth', 'signed'])
->name('verification.verify');