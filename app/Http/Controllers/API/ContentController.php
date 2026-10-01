<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Content;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Models\ContentCompletion;
use App\Models\Course;
use Illuminate\Http\Request;

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

    public function completion(Request $request, $id)
    {
        $validationData = $request->validate([
            'content_type' => 'nullable|in:podcast,video,activity,evaluation',
            'content_id'   => 'nullable|integer',
            'is_complete'  => 'nullable|in:Yes,No',
        ]);

        $user = auth()->user();

        $course = Course::find($id);

        if (!$course) {
            return Helper::JsonErrorResponse('Course not found', 404);
        }

        $query = Content::query();

        // Always filter by course
        $query->where('course_id', $id);

        // If content_id is provided (this is the actual related model ID, e.g. video_id)
        if ($request->filled('content_id')) {
            $query->where('contentable_id', $request->content_id);
        }

        // If content_type is provided, map it to contentable_type
        if ($request->filled('content_type')) {
            $map = [
                'video'      => 'App\\Models\\Video',
                'podcast'    => 'App\\Models\\Podcast',
                'activity'   => 'App\\Models\\Activity',
                'evaluation' => 'App\\Models\\Evaluation',
            ];

            if (isset($map[$request->content_type])) {
                $query->where('contentable_type', $map[$request->content_type]);
            }
        }

        $content = $query->first();

        if (!$content) {
            return Helper::JsonErrorResponse('Content not found', 404);
        }

        $isComplete = $request->input('is_complete', 'Yes');
        $contentType = $request->input('content_type', $content->type);

        $content_completion = ContentCompletion::where('user_id', $user->id)->where('content_id', $content->id)->first();

        if($content_completion){
            $content_completion->update([
                'course_id' => $course->id,
                'is_completed' => $isComplete,
                'type' => $contentType,
            ]);

            return Helper::JsonResponse(
                true,
                'Content data fetch successfully',
                200,
                new ContentResource($content)
            );
        }

        ContentCompletion::create([
           'user_id' => $user->id,
           'content_id' => $content->id,
           'course_id' => $course->id,
           'is_completed' => $isComplete,
           'type' => $contentType,
        ]);

        return Helper::JsonResponse(
            true,
            'Content data fetch successfully',
            200,
            new ContentResource($content)
        );

    }


}
