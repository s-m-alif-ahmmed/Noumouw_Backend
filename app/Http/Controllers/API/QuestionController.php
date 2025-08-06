<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Helpers\Helper;
use App\Http\Resources\QuestionResource;

class QuestionController extends Controller
{
    public function index()
    {
        try {
            $questions = Question::with('evaluation:id,title')->get();
            return Helper::JsonResponse(true, 'Question data fetch successfully', 200, QuestionResource::collection($questions));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }


    public function show(string $id)
    {
        try {
            $question = Question::with('evaluation:id,title')->find($id);

            if (!$question) {
                return Helper::jsonErrorResponse('Question not found', 404);
            }
            return Helper::JsonResponse(true, 'Question data fetch successfully', 200, new QuestionResource($question));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
