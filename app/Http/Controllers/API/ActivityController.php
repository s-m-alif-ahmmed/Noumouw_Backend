<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Activity;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $activities = Activity::all();
            return Helper::jsonResponse(true, 'Activities data fetch successfully', 200, ActivityResource::collection($activities));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {

        try {
            $activity = Activity::find($id);
           if(!$activity){
               return Helper::jsonErrorResponse('Activity not found', 404);
           }
            return Helper::jsonResponse(true, 'Activity data fetch successfully', 200, new ActivityResource($activity));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);

        }
    }
}
