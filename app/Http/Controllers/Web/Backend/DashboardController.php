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
use App\Models\SupportContact;
use App\Models\UserSubscription;
use Carbon\Carbon;

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
        // $subscriptions = SubscriptionPlan::count();
        $subscriptions = UserSubscription::sum('subscription_price');
        $support = SupportContact::count();
        $pendingSupport = SupportContact::where('status', 'pending')->count();
        $resolvedSupport = SupportContact::where('status', 'resolved')->count();
        $closedSupport = SupportContact::where('status', 'closed')->count();

        // --- Dynamic Enrollment Analytics ---
        // Pre-compute enrollment data for 7-day and 30-day ranges
        $enrollmentData = self::computeEnrollmentData(7);
        $enrollmentDays7 = $enrollmentData['days'];
        $enrollmentCounts7 = $enrollmentData['counts'];

        $enrollmentData30 = self::computeEnrollmentData(30);
        $enrollmentDays30 = $enrollmentData30['days'];
        $enrollmentCounts30 = $enrollmentData30['counts'];

        return view('backend.layout.dashboard_new.index', compact(
            'courses',
            'users',
            'instructors',
            'videos',
            'activities',
            'podcasts',
            'evaluations',
            'questions',
            'subscriptions',
            'support',
            'pendingSupport',
            'resolvedSupport',
            'closedSupport',
            'enrollmentDays7',
            'enrollmentCounts7',
            'enrollmentDays30',
            'enrollmentCounts30',
        ));
    }

    /**
     * Compute enrollment data grouped by day for the last $days days.
     */
    private static function computeEnrollmentData(int $days): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $enrollments = UserSubscription::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $daysList = [];
        $countsList = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $daysList[] = $days <= 7 ? $date->format('D') : $date->format('M d');
            $countsList[] = $enrollments[$key] ?? 0;
        }

        return ['days' => $daysList, 'counts' => $countsList];
    }
}
