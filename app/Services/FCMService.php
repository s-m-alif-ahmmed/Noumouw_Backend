<?php

namespace App\Services;

use App\Models\FirebaseToken;
use App\Models\FirebaseTokens;
use Kreait\Firebase\Factory;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
        $this->messaging = $factory->createMessaging();



        // dd($this->messaging);
    }

    public function sendMessage($token, $title, $body, $data = [])
    {
        $message = [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]),
        ];

        $this->messaging->send($message);
    }

    public function sendCallMessage($token, $title, $body, $data = [])
    {
        $firebaseToken = FirebaseTokens::with('user')
            ->where('token', $token)
            ->first();

        if (!$firebaseToken) {
            return;
        }

        if (!$firebaseToken->user) {
            return;
        }

        if (!$firebaseToken->user->push_notification) {
            return;
        }


        $message = [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => array_merge($data, [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]),
        ];
        $this->messaging->send($message);
    }

    public function notifyUser($user, $type, $call)
    {
        $fcmService = new FCMService();

        $tokens = FirebaseTokens::where('user_id', $user->id)->get();

        foreach ($tokens as $device) {

            if (!empty($device->token)) {

                $fcmService->sendCallMessage(
                    $device->token,
                    'Call Update',
                    $type,
                    [
                        'type' => $type,
                        'call_id' => (string)$call->id
                    ]
                );
            }
        }
    }
}
