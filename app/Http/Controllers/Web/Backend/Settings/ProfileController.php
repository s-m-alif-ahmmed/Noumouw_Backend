<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display the profile Settings page.
     *
     * @return View
     */
    public function index()
    {
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        return view('backend.layout.setting.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|max:100|min:2',
//            'user_name' => 'required|unique:users,user_name,' . auth()->user()->id,
//            'phone_number' => 'required|numeric|unique:users,phone,' . auth()->user()->id,
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);
        $user = User::find(auth()->user()->id);
        $user->name = $request->full_name;
//        $user->user_name = $request->user_name;
//        $user->phone = $request->phone_number;
        $user->email = $request->email;
        $user->save();
        flash()->success('Your profile has been updated.');
        return redirect()->back();
    }

    public function UpdateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

      try{
        $user = Auth::user();
        $image = $request->file('profile_picture');
        $imageName = time() . '.' . $image->getClientOriginalExtension();


        if($user->avatar && file_exists(public_path($user->avatar))){
            Helper::fileDelete(public_path($user->avatar));
        }

        $imagePath =  Helper::fileUpload($image , 'profile' , $imageName);

        if($imagePath === null){
            throw new Exception('Failed to upload image');
        }

        //! update user avatar with the new image path
        $user->avatar = $imagePath;
        $user->save();

        flash()->success('Profile Picture Updated Successfully');
        return redirect()->back();

      }catch(\Exception $e){
        flash()->error('Something went wrong');
        return redirect()->back();
      }
    }

    /**
     * Update the user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function UpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = Auth::user();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();

                flash()->success('Password Updated Successfully');
                return redirect()->back();
            } else {
                flash()->error('Current password is incorrect');
                return redirect()->back();
            }
        } catch (Exception) {
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }
}
