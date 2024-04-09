<?php


namespace App\Actions;

use App\Models\PasswordReset;
use App\Models\RegistrationVerification;
use App\Models\User;
use App\Notifications\Auth\EmailVerificationNotification;
use App\Notifications\Auth\PasswordResetNotification;
use App\Notifications\Auth\WelcomeNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AuthenticationActions
{

    /**
     * @throws \Exception
     */
    public static function register($request): bool
    {
        DB::beginTransaction();
        try {
            //create user
            $user = User::create([
                'fullname'=>$request->fullname,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'password'=>Hash::make($request->password),
                'referral' => $request->referral
            ]);

            $token = rand(10000, 99999);
            //create registration verification
            RegistrationVerification::create([
                'user_id'=>$user->id,
                'email_token'=>Hash::make($token)
            ]);

            $user->notify( new WelcomeNotification());

            $user->notify(new EmailVerificationNotification($token));

            DB::commit();
            return true;
        }catch (\Throwable $throwable){
            DB::rollBack();
            report($throwable);
            throw new \Exception('An error occurred please try again', 400);
        }
    }

    /**
     * @throws \Exception
     */
    public static function login($request): string
    {

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            throw new \Exception('Invalid credentials', 400);
        }

        if (auth()->user()->email_verified_at == null)
         {
             throw new \Exception('Please verify your email address to continue', 403);
         }

        return auth()->user()->createToken('basic-access-token')->plainTextToken;
    }

    /**
     * @throws \Exception
     */

    /**
     * @throws \Exception
     */
    public static function submitPasscode($request): bool
    {
        try {
            $user = User::where('email', $request->email)
                ->first();
            if ($user)
            {
                $user = User::where('email', $request->email)->first();
                $user->passcode = Hash::make($request->passcode);
                $user->save();
                return true;
            }

            throw new \Exception('Something Went Wrong, Try Again', 400);
        }catch (\Throwable $throwable){
            report($throwable);
            throw new \Exception('An error occurred, please try again', 400);
        }
    }

    /**
     * @throws \Exception
     */
    public static function passcodeLogin($request): string
    {
        if (! Hash::check($request->passcode, $request->user()->passcode))
        {
            throw new \Exception('Invalid credentials', 400);
        }

        if (auth()->user()->email_verified_at == null)
        {
            throw new \Exception('Please verify your email address to continue', 403);
        }

        return auth()->user()->createToken('basic-access-token')->plainTextToken;
    }

    /**
     * @throws \Exception
     */
    public static function sendPasswordResetToken($request): bool
    {
        $token = rand(10000, 99999);
        $tokenHash = Hash::make($token);

        try {
            PasswordReset::updateOrCreate(
                ['email' =>  $request->email],
                ['token' => $tokenHash]
            );

            $user = User::where('email', $request->email)->first();
            Notification::send($user, new PasswordResetNotification($token));
            //Log::warning($token);
            return true;
        }catch (\Throwable $throwable){
            report($throwable);
            throw new \Exception('Sorry an error occurred, please try again', 400);
        }
    }

    /**
     * @throws \Exception
     */
    public static function submitPasswordResetToken($request): bool
    {
        try {
            $token = PasswordReset::where('email', $request->email)
                ->first();
            if (Hash::check($request->token, $token->token)) {
                $user = User::where('email', $token->email)->first();
                $user->password = Hash::make($request->password);
                $user->save();
                return true;
            }

            throw new \Exception('Expired token', 400);
        }catch (\Throwable $throwable){
            report($throwable);
            throw new \Exception('An error occurred, please try again', 400);
        }
    }

    /**
     * @throws \Exception
     */
    public static function verifyRegistrationOtp($request): bool
    {
        try {
            $user = User::where('email', $request->email)->first();
            $otp = RegistrationVerification::where('user_id', $user->id)
                ->where('email_token_used_at', null)
                ->first();

            if (!$otp){
                throw new \Exception('Expired token', 400);
            }
            if (!Hash::check($request->email_token, $otp->email_token))
            {
                throw new \Exception('Invalid token supplied', 400);
            }
            $user->email_verified = 1;
            $user->email_verified_at = Carbon::now();
            $otp->email_token_used_at = Carbon::now();
            $otp->save();
            $user->save();
            return true;
        }catch (\Throwable $throwable){
            report($throwable);
            throw new \Exception('Sorry an error occurred, please try again', 400);
        }
    }

    /**
     * @throws \Exception
     */
    public static function resendEmailToken($request): bool
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $user->save();
            $verification = RegistrationVerification::firstOrCreate([
                'user_id'=>$user->id
            ]);
            $otp = rand(10000, 99999);
            //send verification code to email
            $verification->email_token = Hash::make($otp);

            $user->notify(new EmailVerificationNotification($otp));
            $verification->save();
            DB::commit();
            return true;
        }catch (\Throwable $throwable){
            DB::rollBack();
            report($throwable);
            throw new \Exception('An error occurred, please try again', 400);
        }
    }
}
