<?php

namespace App\Http\Controllers\Web\Backend;
use App\Models\Activity;
use App\Models\Evaluation;
use App\Models\Podcast;
use App\Models\Question;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::count();
        $users = User::where('role', 'user')->count();
        $instructors = Instructor::count();
        $videos = Video::count();
        $activities = Activity::count();
        $podcasts = Podcast::count();
        $evaluations = Evaluation::count();
        $questions = Question::count();
        $subscriptions = SubscriptionPlan::count();

        return view('backend.layout.dashboard.index',compact(
            'courses',
            'users',
            'instructors',
            'videos',
            'activities',
            'podcasts',
            'evaluations',
            'questions',
            'subscriptions',
        ));
    }

}
