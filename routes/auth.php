<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticateController::class, 'login']);
Route::post('register', [AuthenticateController::class, 'register']);
Route::post('resend', [AuthenticateController::class, 'resend']);
Route::post('verify', [AuthenticateController::class, 'verify']);

Route::post('forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('reset', [ForgotPasswordController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthenticateController::class, 'logout']);
    Route::post('me', [AuthenticateController::class, 'me']);
});
