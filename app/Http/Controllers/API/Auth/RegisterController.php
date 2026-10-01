<?php

namespace App\Http\Controllers\API\Auth;


use Carbon\Carbon;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;



class RegisterController extends Controller
{
    public function register(RegisterRequest $request){
        try {
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatar = Helper::fileUpload($request->file('avatar'), 'user/avatar',getFileName($request->file('avatar')));
            }else{
                $avatar = null;
            }
            
            \DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'avatar' => $avatar,
            ]);

            $user->profile()->create([
                'birth_date' => $request->date_of_birth,
                'parent_role' => $request->parent_role,
                'is_parent' => $request->is_parent,
                'country' => $request->country
            ]);

            foreach ($request?->children ?? [] as $child) {
                $user->children()->create([
                    'name' => $child['name'],
                    'birth_date' => $child['date_of_birth'],
                ]);
            }

            $otp = $this->send_otp($user);

            \DB::commit();

            return Helper::jsonResponse(true,'Register successfully',201, [
                'otp' => $otp->token,
            ]);

        }catch (\Exception $exception){
            \DB::rollBack();
            return Helper::jsonErrorResponse($exception->getMessage(),500);
        }
    }


    public function send_otp(User $user,$mailType = 'verify')
    {
        $otp  = (new Otp)->generate($user->email, 'numeric', 6, 60);
        $message = $mailType === 'verify' ? 'Verify Your Email Address' : 'Reset Your Password';
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            \Mail::to($user->email)->send(new \App\Mail\OTP($otp->token,$user,$message,$mailType));
        }
        return $otp;
    }

    public function resend_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            if($user){
                $this->send_otp($user);
                return Helper::jsonResponse(true,'OTP send successfully.',201);
            }else{
                return Helper::jsonErrorResponse('Email not found',404);
            }
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),500);
        }
    }

    public function verify_email(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|digits:6',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if(!$user){
                return Helper::jsonErrorResponse('Email not found',404);
            }

            if($user->email_verified_at !== null){
                return Helper::jsonErrorResponse('Email already verified',404);
            }

            $verify = (new Otp)->validate($request->email, $request->otp);
            if($verify->status){
                $user->email_verified_at = now();
                $user->save();

                $token =  $user->createToken('AuthToken')->plainTextToken;

                return Helper::jsonResponse(true,'Email verified successfully',200, $token);
            }else{
                return Helper::jsonErrorResponse($verify->message,404);
            }
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),500);
        }
    }

    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        try {
            $user = User::where('email', $request->email)->first();
            if(!$user){
                return Helper::jsonErrorResponse('Email not found',404);
            }
            $this->send_otp($user,'forget');
            return Helper::jsonResponse(true,'OTP send successfully.',201);
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),500);
        }
    }

    public function verify_otp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|digits:6',
        ]);

        $verify = (new Otp)->validate($request->email, $request->otp);
        if($verify->status){
            $user = User::where('email', $request->email)->first();
            if(!$user){
                return Helper::jsonErrorResponse('Email not found',404);
            }
            $user->reset_password_token = \Str::random(15);
            $user->reset_password_token_exp = Carbon::now()->minutes(15);
            $user->save();
            return Helper::jsonResponse(true,'Email verified successfully',200,[
                'token' => $user->reset_password_token,
            ]);
        }else{
            return Helper::jsonErrorResponse($verify->message,404);
        }
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        try {
            $user = User::where('email',$request->email)->where('reset_password_token', $request->token)->first();

            if(!$user){
                return Helper::jsonErrorResponse('Invalid Token',404);
            }

            if ($user->reset_password_token_exp > Carbon::now()) {
                return  Helper::jsonErrorResponse('Token expired', 404);
            }

            $user->password = bcrypt($request->password);
            $user->reset_password_token = null;
            $user->reset_password_token_exp = null;
            $user->save();
            return Helper::jsonResponse(true,'Password reset successfully',200);
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),404);
        }
    }
}
