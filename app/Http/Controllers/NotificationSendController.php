<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\User;
use Flasher\Laravel\Facade\Flasher;
use Yajra\DataTables\DataTables;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class NotificationSendController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'user')
                ->select('id', 'name', 'avatar', 'email')
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($data) {
                    $url = $data->avatar
                        ? asset($data->avatar)
                        : asset('backend/images/user-2.jpg');

                    return '<img src="' . $url . '" class="rounded-full w-12 h-12 object-cover">';
                })
                ->addColumn('action', function ($data) {
                    return '<input type="checkbox" class="form-checkbox user-checkbox" name="user_ids[]" value="' . $data->id . '">';
                })
                ->rawColumns(['avatar', 'action'])
                ->make(true);
        }

        return view('backend.layout.push-notification.index');
    }


    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_selection_type' => 'required|in:all,specific',
            'user_ids' => 'nullable|required_if:user_selection_type,specific|array',
        ]);

        try {
            $firebaseMessaging = $this->getFirebaseMessagingInstance();

            $notification = FirebaseNotification::create($request->title, $request->description);

            if ($request->file('image') && $request->file('image')->isValid()) {
                $path = Helper::fileUpload($request->file('image'), 'notification', getFileName($request->file('image')));
                $notification->withImageUrl(url($path));
            }


            // Determine which users to notify
            $usersToNotify = $this->getUsersToNotify($request);

            // Send notifications
            foreach ($usersToNotify as $user) {
                foreach ($user->firebaseTokens as $token) {
                    $this->sendNotificationToUser($firebaseMessaging, $notification,$token->token);
                }

            }

            return redirect()->back()->with('success', 'Notification sent successfully');
        } catch (\Exception $exception) {
            dd($exception);
            flash()->error('Failed to send notification. Please try again.');
            return redirect()->back();
        }
    }

    private function getFirebaseMessagingInstance()
    {
        $factory = (new Factory)->withServiceAccount(storage_path('app/private/noumouw-83ed9-firebase-adminsdk-alhl9-4830ef64d7.json'));
        return $factory->createMessaging();
    }

    private function getUsersToNotify(Request $request)
    {
        if ($request->user_selection_type === 'all') {
            return User::where('role', 'user')
                ->with(['firebaseTokens' => function ($query) {
                    $query->where('is_active', '1');
                }])
                ->get();
        }

        if ($request->user_selection_type === 'specific') {
            return User::whereIn('id', $request->user_ids)
                ->with(['firebaseTokens' => function ($query) {
                    $query->where('is_active', '1');
                }])
                ->get();
        }


        return collect(); // return an empty collection if no valid user selection type
    }

    private function sendNotificationToUser($messaging, $notification,$token)
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }


}
