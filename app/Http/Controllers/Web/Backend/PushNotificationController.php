<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\SendPushNotification;

class PushNotificationController extends Controller
{
    public function index()
    {
        // Fetch users with 'user' role
        $data = User::where('role', 'user')->select('id', 'name', 'avatar', 'email')->latest()->get();
        return view('backend.layout.push-notification.index', compact('data'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle image upload
            $imagePath = $request->hasFile('image')
                ? $request->file('image')->store('notifications', 'public')
                : null;


            $users = User::whereIn('id', $request->user_ids)->get();
            foreach ($users as $user) {
                $user->notify(new SendPushNotification($request->title, $request->description, $imagePath));
          
            }

            return redirect()->back()->with('success', 'Notifications sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
