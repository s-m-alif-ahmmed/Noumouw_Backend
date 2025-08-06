<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionPlanResource;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = SubscriptionPlan::all();
            return Helper::jsonResponse(true, 'subscription plan data fetch successfully', 200, SubscriptionPlanResource::collection($subscriptions));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $subscription = SubscriptionPlan::find($id);

            if (!$subscription) {
                return Helper::jsonErrorResponse('subscription plan not found', 404);
            }
            return Helper::jsonResponse(true, 'subscription plan data fetch successfully', 200, new SubscriptionPlanResource($subscription));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
