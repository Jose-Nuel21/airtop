<?php

namespace App\Http\Controllers\API;

use App\Actions\UserActions;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected ?\Illuminate\Contracts\Auth\Authenticatable $user;

    public function __construct()
    {
        $this->user = auth()->guard('sanctum')->user();
    }

    public function updateProfile(Request $request)
    {
        $v = Validator::make( $request->all(), [
            'fullname'  => 'string',
            'phone'     => 'string',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            $user = UserActions::updateProfile($request, $this->user);
            return $this->successResponse($user, 'Profile Updated Successfully');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }

    }

    public function uploadPhoto(Request $request)
    {
        $v = Validator::make( $request->all(), [
            'profile_image'  => 'image',
        ]);

        if($v->fails()){
            return $this->validationErrorResponse($v->errors());
        }

        try {
            $user = UserActions::uploadPhoto($request, $this->user);
            return $this->successResponse($user, 'Photo Updated Successfully');
        }catch (\Throwable $throwable){
            return $this->errorResponse([], $throwable->getMessage());
        }
    }
}
