<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;

class ContentController extends Controller
{
    public function index()
    {
       try{
        $contents = Content::with(['course:id,name,thumbnail'])->get();

        if(!$contents){
            return Helper::jsonErrorResponse('Content not found', 404);
        }


        return Helper::JsonResponse(true, 'Content data fetch successfully', 200, ContentResource::collection($contents));
       }catch(\Exception $exception){
        return Helper::jsonErrorResponse($exception->getMessage(), 500);
       }
    }


    public function show(string $id)
    {
        try {
            $content = Content::with(['course:id,name,thumbnail'])->find($id);


            if (!$content) {
                return Helper::JsonErrorResponse('Content not found', 404);
            }

            return Helper::JsonResponse(true, 'Content data fetch successfully', 200, new ContentResource($content));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
