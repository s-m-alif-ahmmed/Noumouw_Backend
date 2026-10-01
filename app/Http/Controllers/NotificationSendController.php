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
            $sentCount = 0;
            foreach ($usersToNotify as $user) {
                if ($user->firebaseTokens->isEmpty()) {
                    \Log::info("No active Firebase tokens found for user ID: {$user->id}");
                    continue;
                }

                foreach ($user->firebaseTokens as $token) {
                    try {
                        if (empty($token->token)) {
                            \Log::warning("Empty token for user ID: {$user->id}");
                            continue;
                        }

                        \Log::info("Sending to token: {$token->token}");

                        $this->sendNotificationToUser($firebaseMessaging, $notification, $token->token);
                        $sentCount++;

                    } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                        // ❌ Token is invalid / deleted
                        \Log::warning("Invalid token (NotFound): {$token->token}");

                        $token->update(['is_active' => '0']); // deactivate token

                    } catch (\Kreait\Firebase\Exception\Messaging\InvalidArgument $e) {
                        // ❌ Malformed token
                        \Log::warning("Invalid token format: {$token->token}");

                        $token->update(['is_active' => '0']);

                    } catch (\Kreait\Firebase\Exception\MessagingException $e) {
                        // ❌ Other FCM errors
                        \Log::error("FCM error for token {$token->token}: " . $e->getMessage());

                    } catch (\Exception $e) {
                        // ❌ Any unexpected error
                        \Log::error("Unexpected error: " . $e->getMessage());
                    }
                }

            }

            return redirect()->back()->with('success', "Notification sent successfully.");
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
        $query = User::where('role', 'user')
            ->whereHas('firebaseTokens', function ($q) {
                $q->where('is_active', '1');
            })
            ->with(['firebaseTokens' => function ($q) {
                $q->where('is_active', '1');
            }]);


        if ($request->user_selection_type === 'specific') {
            $query->whereIn('id', $request->user_ids);
        }

        return $query->get();
    }

    private function sendNotificationToUser($messaging, $notification,$token)
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }


}
