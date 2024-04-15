<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function(){
    Route::post('login', [\App\Http\Controllers\API\AuthenticationController::class, 'login']);
    Route::post('passcode_login', [\App\Http\Controllers\API\AuthenticationController::class, 'loginWithPasscode']);
    Route::post('register', [\App\Http\Controllers\API\AuthenticationController::class, 'registration']);
    Route::post('submit_passcode', [\App\Http\Controllers\API\AuthenticationController::class, 'submitPasscode']);
    Route::post('send_password_reset_token', [\App\Http\Controllers\API\AuthenticationController::class, 'sendPasswordResetToken']);
    Route::post('submit_password_reset_token', [\App\Http\Controllers\API\AuthenticationController::class, 'submitPasswordResetToken']);
    Route::post('verify_email', [\App\Http\Controllers\API\AuthenticationController::class, 'verifyRegistrationEmail']);
    Route::post('resend_email_otp', [\App\Http\Controllers\API\AuthenticationController::class, 'resendEmailVerification']);

    Route::middleware('auth:sanctum')->group(function (){
        Route::post('update_profile', [\App\Http\Controllers\API\UserController::class, 'updateProfile']);
        Route::post('upload_photo', [\App\Http\Controllers\API\UserController::class, 'uploadPhoto']);
    });
});
