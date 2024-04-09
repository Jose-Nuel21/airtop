<?php

namespace App\Http\Controllers\API;

use App\Actions\AuthenticationActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function registration(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone'=> "required|min:10|unique:users",
            'referral' => 'nullable|string'
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::register($request);
            return $this->successResponse([], 'Registration successful, please verify your email address');
        }catch (\Throwable $th){
            return $this->errorResponse([], $th->getMessage());
        }
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            //try authenticating the user
            $token = AuthenticationActions::login($request);
            return $this->successResponse($token, 'Login successful');
        }catch (\Throwable $th){
            //if the error code is 400, credentials are invalid
            if ($th->getCode() == 400)
                return $this->errorResponse([], $th->getMessage());
            //if the response code is 403, email is not verified
            elseif ($th->getCode() == 403)
                return $this->errorResponse([
                    'email'=>auth()->user()->email,
                    'required'=>['email'=>true]
                ], $th->getMessage(), 403);
            //else this error details should be seen only by engineer
            report($th);
            return $this->errorResponse([], 'Sorry an error occurred, our engineers has been notified');
        }
    }

    public function submitPasscode(Request $request)
    {
        $v = Validator::make( $request->all(), [
            'email' => 'required|exists:users,email',
            'passcode' => 'required|string|min:5',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::submitPasscode($request);
            return $this->successResponse([], 'Passcode set successfully');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }

    public function loginWithPasscode(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'passcode' => 'required|string',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            //try authenticating the user
            $token = AuthenticationActions::passcodeLogin($request);
            return $this->successResponse($token, 'Login successful');
        }catch (\Throwable $th){
            //if the error code is 400, credentials are invalid
            if ($th->getCode() == 400)
                return $this->errorResponse([], $th->getMessage());
            //if the response code is 403, email is not verified
            elseif ($th->getCode() == 403)
                return $this->errorResponse([
                    'email'=>auth()->user()->email,
                    'required'=>['email'=>true]
                ], $th->getMessage(), 403);
            //else this error details should be seen only by engineer
            report($th);
            return $this->errorResponse([], 'Sorry an error occurred, our engineers has been notified');
        }
    }

    public function sendPasswordResetToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::sendPasswordResetToken($request);
            return $this->successResponse([], 'An OTP has been sent to your email address');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }

    public function submitPasswordResetToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'email'=>'required|string|email|max:255|exists:users,email',
            'token'=>'required|string',
            'password'=>'required|string|min:6|confirmed'
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::submitPasswordResetToken($request);
            return $this->successResponse([], 'Password updated successfully');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }

    public function verifyRegistrationEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'email' => 'required|string|exists:users,email',
            'email_token' => 'required|string|min:5',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::verifyRegistrationOtp($request);
            return $this->successResponse([], 'Email verified successfully');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }

    public function resendEmailVerification(Request $request): \Illuminate\Http\JsonResponse
    {
        $v = Validator::make( $request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            AuthenticationActions::resendEmailToken($request);
            return $this->successResponse([], 'A token has been sent to '.$request->email);
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }
}
