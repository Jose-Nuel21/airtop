<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [\App\Http\Controllers\API\AuthenticationController::class, 'login']);
Route::post('passcode_login', [\App\Http\Controllers\API\AuthenticationController::class, 'loginWithPasscode']);
Route::post('register', [\App\Http\Controllers\API\AuthenticationController::class, 'registration']);
Route::post('submit_passcode', [\App\Http\Controllers\API\AuthenticationController::class, 'submitPasscode']);
Route::post('send_password_reset_token', [\App\Http\Controllers\API\AuthenticationController::class, 'sendPasswordResetToken']);
Route::post('submit_password_reset_token', [\App\Http\Controllers\API\AuthenticationController::class, 'submitPasswordResetToken']);
Route::post('verify_email', [\App\Http\Controllers\API\AuthenticationController::class, 'verifyRegistrationEmail']);
Route::post('resend_email_otp', [\App\Http\Controllers\API\AuthenticationController::class, 'resendEmailVerification']);
