<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\UserTag;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
         $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|string',
         ]);
         if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
             return Helper::jsonErrorResponse('The provided credentials do not match our records.',401,[
                 'email' => 'The provided credentials do not match our records.'
             ]);
         }

         if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) && Auth::user()->email_verified_at === null){

//             $otp  = (new Otp)->generate($request->email, 'numeric', 6, 60);
//
//             return Helper::jsonResponse(true, 'Email not verified.', 403, ['otp' => $otp->token]);

             return Helper::jsonErrorResponse('Email not verified.',403);
         }

         $user = Auth::user();
         return response()->json([
             'status' => true,
             'message' => 'Login Successful',
             'token_type' => 'Bearer',
             'token' => $user->createToken('AuthToken')->plainTextToken,
             'data' => $user
         ]);
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the current user’s token
            $request->user()->currentAccessToken()->delete();
            // Return a response indicating the user was logged out
            return response()->json(['message' => 'Logged out successfully.'], 200);
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(),401,[]);
        }
    }

    public function user()
    {
        return Helper::jsonResponse(true,'User Details fetch successfully.',200,Auth::user()->load('profile','children'));
    }

    public function userTags()
    {
        $user = Auth::user();
        $userTags = UserTag::where('user_id', $user->id)->get();
        // Always initialize your array first
        $userAllTags = [];

        if ($userTags->isEmpty()) {
            return Helper::jsonResponse(true, 'User Tags fetch successfully.', 200, $userTags);
        }

        $tags = Tag::get();

        if ($tags->isEmpty()) {
            return Helper::jsonResponse(true, 'Tags Not Found.', 200, $tags);
        }

        foreach ($userTags as $userTag){
            foreach ($tags as $tag){
                if ($tag->id == $userTag->tag_id){
                    $userAllTags[] = $tag;
                }
            }
        }

        return Helper::jsonResponse(true,'User Details fetch successfully.',200, $userAllTags);
    }

    public function profile_update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'avatar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date_of_birth' => 'sometimes|required|date|before:today|date_format:Y-m-d',
            'parent_role' => 'sometimes|required|in:father,mother',
            'is_parent' => 'sometimes|required|boolean',
            'country' => 'sometimes|required|string',
            'children' => 'sometimes|required|array',
            'children.*.name' => 'sometimes|required|string',
            'children.*.date_of_birth' => 'sometimes|required|date|before:today|date_format:Y-m-d',
        ]);

        try {
            $user = auth()->user();

            if (!$user) {
                return Helper::jsonErrorResponse('User not found.', 404);
            }

            // Handle Avatar Upload
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                // Delete old avatar
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }

                // Generate a unique file name
                $randomFileName = Str::random(20); // Generates a 20-character random string

                // Upload new avatar with a unique name
                $avatar = Helper::fileUpload($request->file('avatar'), 'user/avatar', $randomFileName);
            } else {
                $avatar = $user->avatar;
            }

            // Update User
            $user->update([
                'name' => $request->filled('name') ? $request->name : $user->name,
                'avatar' => $avatar,
            ]);

            // Update or Create Profile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'birth_date' => $request->filled('date_of_birth') ? $request->date_of_birth : $user->profile->birth_date ?? null,
                    'parent_role' => $request->filled('parent_role') ? $request->parent_role : $user->profile->parent_role ?? null,
                    'is_parent' => $request->has('is_parent') ? (bool) $request->is_parent : $user->profile->is_parent ?? false,
                    'country' => $request->filled('country') ? $request->country : $user->profile->country ?? null,
                ]
            );

            // Update Children
            if ($request->has('children')) {
                $user->children()->delete(); // Clear old records
                foreach ($request->children as $child) {
                    $user->children()->create([
                        'name' => $child['name'],
                        'birth_date' => $child['date_of_birth'],
                    ]);
                }
            }

            return Helper::jsonResponse(true, 'Profile updated successfully.', 200, $user->load('profile', 'children'));

        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }


}
