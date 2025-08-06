<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class VideoController extends Controller
{
    public function index()
    {
        try {
            $videos = Video::all();
            return Helper::jsonResponse(true,'Videos data fetch successfully', 200, VideoResource::collection($videos));
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse( $exception->getMessage(),500);
        }
    }

    public function show(string $id)
    {
        try {
            $video = Video::with('instructor:id,name,avatar', 'content.course:id,name')->find($id);
            if(!$video){
                return Helper::jsonErrorResponse('video not found',404 );
            }
            return Helper::jsonResponse(true,'Video fetch successfully', 200, new VideoResource($video));
        }catch (\Exception $exception){
            return Helper::jsonErrorResponse( $exception->getMessage(),500);
        }
    }

    // Method to update video progress
    public function updateProgress(Request $request, $videoId)
    {
        $request->validate([
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update or create the progress record for the user and video
        $progress = VideoProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'video_id' => $videoId,
            ],
            [
                'progress_percentage' => $request->input('progress'),
            ]
        );

        return response()->json([
            'message' => 'Progress updated successfully!',
            'progress' => $progress,
        ]);
    }

    // Method to get video progress for a user
    public function getVideoProgress($videoId)
    {
        $user = Auth::user();

        // Retrieve the user's progress for the video
        $progress = VideoProgress::where('user_id', $user->id)
            ->where('video_id', $videoId)
            ->first();

        // If there's no progress, return 0
        return response()->json([
            'progress' => $progress ? $progress->progress_percentage : 0,
        ]);
    }

}
