<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Helpers\Helper;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(){
        try{
            $tags = Tag::paginate(10);
            if (!$tags) {
                return Helper::jsonErrorResponse('Tags not found', 404);
            }
            return Helper::jsonResponse(true, 'Tags fetch successfully', 200, TagResource::collection($tags));
        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id){
        try{
            $tag = Tag::find($id);
            if (!$tag) {
                return Helper::jsonErrorResponse('Tag not found', 404);
            }
            return Helper::jsonResponse(true, 'Tag fetch successfully', 200, new TagResource($tag));
        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function aiTagStore(Request $request)
    {
        $validated = $request->validate([
            'tags' => ['required', 'array'],
            'tags.*' => ['string'],
        ]);

        if (!$validated) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validated->errors(),
            ], 422);
        }

        $user = auth()->user();

        $user->user_tags()->sync($validated['tags']);

        return Helper::jsonResponse(true, 'Tag added successfully', 200);

    }

}
