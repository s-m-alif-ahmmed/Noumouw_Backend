<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ContentCompletion;
use App\Models\Course;
use App\Helpers\Helper;
use App\Http\Resources\CourseResource;
use App\Models\Evaluation;
use App\Models\Podcast;
use App\Models\UserSubscription;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
                ->with(['subscription:id,name,price', 'tags:id,title', 'category:id,name'])
                ->withExists([
                    'subscriptions as is_subscribed' => function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }
                ])
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

    public function progress()
    {
        try {
            $user = auth()->user();

            $subscribedCourseIds = UserSubscription::where('user_id', $user->id)
                ->pluck('course_id')
                ->unique();

            if ($subscribedCourseIds->isEmpty()) {
                return Helper::jsonErrorResponse('No courses found for this user.', 404);
            }

            $courses = Course::whereIn('id', $subscribedCourseIds)
                ->with([
                    'contents' => function ($query) {
                        $query->orderBy('order');
                    },
                    'contents.contentable',
                    'tags'
                ])
                ->get();

            if ($courses->isEmpty()) {
                return Helper::jsonErrorResponse('No courses found for this user.', 404);
            }

            $progressData = $courses->map(function ($course) use ($user) {
                $totalContents = $course->contents->count();

                // Completed contents for this user in this course
                $completedCount = ContentCompletion::where([
                    ['user_id', '=', $user->id],
                    ['course_id', '=', $course->id],
                    ['is_completed', '=', 'Yes']
                ])->count();

                $progressPercent = $totalContents > 0
                    ? round(($completedCount / $totalContents) * 100, 2)
                    : 0;

                return [
                    'course_id' => $course->id,
                    'course_name' => $course->name,
                    'thumbnail' => $course->thumbnail,
                    'total_contents' => $totalContents,
                    'completed_contents' => $completedCount,
                    'progress_percentage' => $progressPercent,
                    'tags' => $course->tags->pluck('name'),
                ];
            });

            return Helper::JsonResponse(true, 'Course progress fetched successfully', 200, $progressData);

        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function course_subscription_plan(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $subscription_plan = Course::select(['id','name', 'status', 'subscription_plans_id'])->with('subscription')->find($request->course_id);

        if (! $subscription_plan) {
            return Helper::jsonErrorResponse('Course not found', 404);
        }

        return Helper::JsonResponse(true, 'Course subscription plan fetched successfully', 200, $subscription_plan);
    }

    public function subscribe(Request $request){

        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $existingSubscription = UserSubscription::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingSubscription) {
            if (!$existingSubscription->is_expired) { 
                return Helper::JsonResponse(false, 'You are already subscribed to this course.', 400);
            }
        }

        $subscription_plan = Course::with(['subscription', 'contents:id,course_id,type'])->find($request->course_id);
        // return Helper::JsonResponse(true, 'You have successcully subscribed to this course', 200, $subscription_plan);

        $user = auth()->user();

        $subscription = DB::transaction(function () use ($request, $subscription_plan, $user) {
            $subscription = UserSubscription::create([
                'course_id' => $request->course_id,
                'user_id' => $user->id,
                'start_date' => now(),
                'end_date' => now()->addDays($subscription_plan->subscription->duration == "monthly"? 30 : 365),
                'subscription_name' => $subscription_plan->subscription->name, //taking snapshot
                'subscription_duration' => $subscription_plan->subscription->duration,
                'subscription_price' => $subscription_plan->subscription->price,
            ]);

            ContentCompletion::where('user_id', $user->id)
                ->where('course_id', $request->course_id)
                ->update(['is_completed' => 'No']);

            foreach ($subscription_plan->contents as $content) {
                ContentCompletion::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'content_id' => $content->id,
                    ],
                    [
                        'course_id' => $request->course_id,
                        'type' => $content->type,
                        'is_completed' => 'No',
                    ]
                );
            }

            return $subscription;
        });

        return Helper::JsonResponse(true, 'You have successcully subscribed to this course', 200, $subscription);
    }

    public function getSubscribedCourses()
    {
        $subscribed_courses = auth()->user()->subscribed_courses()->with('course.ratings.user:id,name,avatar')->get();
        if ($subscribed_courses->isNotEmpty()) {
            return Helper::JsonResponse(true, 'Subscribed courses are fetched successfully', 200, $subscribed_courses);
        }
        return Helper::JsonResponse(false, 'No subscribed course are found!!', 404);
    }


    public function unsubscribe(String $id){
        $course_subscription = UserSubscription::find($id);
        //get the course progress
        // dd( $course_subscription->course_id);
        // dd($course_subscription);
        $progress = ContentCompletion::where('course_id', $course_subscription->course_id)->get();

        if ($progress->isNotEmpty()) {
            ContentCompletion::where('course_id', $course_subscription->course_id)->delete();
        }
        $course_subscription->delete();
        return Helper::JsonResponse(true, 'Unsubscribed and Course progress reset successfully', 200);
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
