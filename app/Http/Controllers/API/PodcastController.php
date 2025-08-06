<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Podcast;
use App\Http\Controllers\Controller;
use App\Http\Resources\PodcastResource;

class PodcastController extends Controller
{
    public function index()
    {
        try{

            $podcast = Podcast::with('instructor:id,name', 'content.course:id,name')->get();
            return Helper::JsonResponse(true, 'Podcast data fetch successfully', 200, PodcastResource::collection($podcast));

        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }


    public function show(string $id)
    {
        try{
            $podcast = Podcast::with('instructor:id,name', 'content.course:id,name')->find($id);

            if(!$podcast){
                return Helper::jsonErrorResponse('Podcast not found', 404);
            }
            return Helper::JsonResponse(true, 'Podcast data fetch successfully', 200, new PodcastResource($podcast));

        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
