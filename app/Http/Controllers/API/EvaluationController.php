<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluationAnswerResource;
use App\Models\Evaluation;
use App\Helpers\Helper;
use App\Http\Resources\EvaluationResource;
use App\Models\EvaluationAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class EvaluationController extends Controller
{
    public function index()
    {
        try {
            $evaluations = Evaluation::with('content.course:id,name')->get();
            return Helper::JsonResponse(true, 'Evaluation data fetch successfully', 200, EvaluationResource::collection($evaluations));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $evaluation = Evaluation::with('content.course:id,name','questions:id,evaluation_id,title,answer,link')->find($id);

            if (!$evaluation) {
                return Helper::jsonErrorResponse('Evaluation not found', 404);
            }
            return Helper::JsonResponse(true, 'Evaluation data fetch successfully', 200, new EvaluationResource($evaluation));
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function getAnswer(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array|min:1',
            'answers.*.course_id' => 'required|exists:courses,id',
            'answers.*.evaluation_id' => 'required|exists:evaluations,id',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 200);
        }

        try {

            // Check if the user has already submitted answers for any of the given evaluation questions
            $alreadyAnswered = collect($request->answers)->filter(function ($answer) use ($user) {
                return EvaluationAnswer::where('user_id', $user->id) // Corrected this line
                ->where('course_id', $answer['course_id'])
                    ->where('evaluation_id', $answer['evaluation_id'])
                    ->where('question_id', $answer['question_id'])
                    ->exists();
            });

            if ($alreadyAnswered->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already given this evaluation.',
                ], 200);
            }

            // Use map to iterate through the answers array and save each entry
            $storedAnswers = collect($request->answers)->map(function ($answer) use ($user) {
                return EvaluationAnswer::create([
                    'user_id' => $user->id, // Corrected this line
                    'course_id' => $answer['course_id'],
                    'evaluation_id' => $answer['evaluation_id'],
                    'question_id' => $answer['question_id'],
                    'answer' => $answer['answer'],
                ]);
            });

            // Return a success response
            return Helper::JsonResponse(true, 'You completed the evaluation successfully!', 200, $storedAnswers);

        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 500,
                'errors' => [],
            ], 500);
        }
    }

    public function evaluationResultShow(string $id)
    {
        try {
            $result = Evaluation::with([
                'questions:id,evaluation_id,title,answer',
                'evaluation_answers.user',
                'evaluation_answers.course',
                'evaluation_answers.question'
            ])->find($id);

            if (!$result) {
                return Helper::jsonErrorResponse('Evaluation result not found', 404);
            }

            // Get the current user's ID
            $userId = Auth::id();

            // Calculate wrong answers for the current user only
            $wrongAnswers = $result->evaluation_answers
                ->where('user_id', $userId)
                ->filter(function ($answer) {
                    // Compare the user's answer with the correct answer from the question
                    return $answer->answer != $answer->question->answer;
                })->map(function ($answer) {
                    return [
                        'question_id' => $answer->question->id ?? null,
                        'title' => $answer->question->title ?? null,
                        'correct_answer' => $answer->question->answer ?? null,
                        'link' => $answer->question->link ?? null,
                    ];
                })->values(); // Ensures it's a sequential array

            // Get the total number of questions
            $totalQuestions = $result->questions->count();

            // Calculate the correct answer count
            $correctAnswers = $totalQuestions - $wrongAnswers->count();

            return Helper::JsonResponse(
                true,
                'Evaluation data fetched successfully',
                200,
                [
                    'result' => [new EvaluationAnswerResource($result, $correctAnswers)],
                    'evaluation' => [
                        'id' => $result->id,
                        'title' => $result->title,
                    ],
                    'wrong_answers' => $wrongAnswers,
                ]
            );
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

}
