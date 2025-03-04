<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API of ' . config('app.name') . '!',
    ]);
});

Route::apiResource('group-features', \App\Http\Controllers\Feature\GroupFeatureController::class);
Route::apiResource('features', \App\Http\Controllers\Feature\FeatureController::class);
Route::apiResource('feature-accesses', \App\Http\Controllers\Feature\FeatureAccessController::class);
