<?php


namespace App\Actions;


use App\Models\User;
use Illuminate\Support\Facades\URL;

class UserActions
{
    public static function updateProfile($request, $user)
    {
        $user = User::find($user->id);
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->phone = $request->input('phone', $user->phone);
        $user->save();

        return User::with('media')->where('id', $user->id)->first();
    }

    public static function uploadPhoto($request, $user)
    {
        $user = User::find($user->id);

        if ($request->has('profile_image')) {
            $user->clearMediaCollection('profile_images');
            $user->addMedia($request->profile_image)->toMediaCollection('profile_images');
        }

        return User::with('media')->where('id', $user->id)->first();
    }
}
