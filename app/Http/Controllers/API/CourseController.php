<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Course;
use App\Helpers\Helper;
use App\Http\Resources\CourseResource;
use App\Models\Evaluation;
use App\Models\Podcast;
use App\Models\Video;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $perPage = $request->input('per_page') ?? 10;

            $user = auth()->user();
            $userTags = $user->user_tags()->pluck('title')->toArray();

            $courses = Course::activeCourse()
                ->with(['subscription:id,name,price', 'tags:id,title'])
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('tags', fn($q) => $q->where('title', 'like', '%' . $search . '%'));
                })
                ->when(!empty($userTags), function ($query) use ($userTags) {
                    $query->where(function ($q) use ($userTags) {
                        $q->whereHas('tags', fn($q2) => $q2->whereIn('title', $userTags));

                        // Polymorphic tag matching for contentable models
                        $q->orWhereHas('contents', function ($q3) use ($userTags) {
                            $q3->whereHasMorph(
                                'contentable',
                                [Video::class, Evaluation::class, Podcast::class, Activity::class],
                                function ($q4) use ($userTags) {
                                    $q4->whereHas('tags', fn($q5) => $q5->whereIn('title', $userTags));
                                }
                            );
                        });
                    });
                })
                ->paginate($perPage);

            if ($courses->isEmpty()) {
                return Helper::jsonResponse(true, 'No Search Result matched', 200, $courses, true);
            }

            return Helper::jsonResponse(true, 'Course data fetched successfully', 200, $courses, true);
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $course = Course::with(['contents' => function($query) {
                $query->orderBy('order');
            }, 'contents.contentable'], 'tags')->find($id);

            if (!$course) {
                return Helper::jsonErrorResponse('Course not found', 404);
            }

            return Helper::JsonResponse(true, 'Course data fetch successfully', 200, new CourseResource($course));
        } catch (\Exception $exception) {

            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    // public function searchCourse(Request $request)
    // {
    //     try{
    //         $search = $request->input('search');
    //         if(!$search){
    //             return Helper::jsonErrorResponse('Search term is required', 400);
    //         }

    //         $courses = Course::with(['tags','contents','subscription'])
    //         ->where('name', 'like', '%' . $search . '%')
    //         ->orWhereHas('tags' , function ($query) use ($search) {
    //             $query->where('title' , 'like', '%' . $search . '%');
    //         })
    //         ->orWhereHas('contents' , function ($query) use ($search) {
    //             $query->where('type' , 'like', '%' . $search . '%');
    //         })
    //         ->orWhereHas('subscription', function ($query) use ($search) {
    //             $query->where('name', 'like', '%' . $search . '%');
    //         })
    //         ->get();


    //             // dd($courses);

    //         return Helper::JsonResponse(true, 'Course data fetch successfully', 200, $courses);
    //     }catch(\Exception $exception){
    //         return Helper::jsonErrorResponse($exception->getMessage(), 500);
    //     }
    // }

}
