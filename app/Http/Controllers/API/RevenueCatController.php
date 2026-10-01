<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class RevenueCatController extends Controller
{
    public function webhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('Authorization');

        if ($signature !== config('services.revenuecat.webhook_secret')) {
            abort(403, 'Unauthorized');
        }

        $event = $payload['event'] ?? null;
        $appUserId = $event['app_user_id'] ?? null;

        $user = is_numeric($appUserId) ? User::find($appUserId) : null;

        if($event['type'] != 'TEST') {
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
        }

        switch ($event['type']) {
            case 'INITIAL_PURCHASE':
            case 'RENEWAL':
                UserSubscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'course_id'       => $event['course_id'] ?? null,
                        'subscription_price'            => $event['subscription_price'] ?? null,
                        'subscription_name'        => $event['subscription_name']?? null,
                        'member_since'     => isset($event['purchased_at_ms']),
                        'start_date'        => $event['start_date']?? null,
                        'end_date'        => $event['end_date']?? null,
                        'subscription_duration'        => $event['subscription_duration']?? null,
                        'status'           => 'active',
                    ]
                );
                break;
            case 'CANCELLATION':
                UserSubscription::where(['user_id' => $user->id])->delete();
                break;
            case 'EXPIRATION':
                    UserSubscription::where('user_id', $user->id)
                    ->delete();
                break;
            case 'TEST':
                \Log::info('Received RevenueCat test webhook', ['payload' => $payload]);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully'
        ]);
    }
}
