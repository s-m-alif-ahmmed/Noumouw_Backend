<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\CourseRating;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class RatingsController extends Controller
{
    /**
     * Get all ratings/reviews for a given course.
     */
    public function index(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id', 
        ]);

        $ratings = CourseRating::where('course_id', $request->course_id)
            ->where('status', true)
            ->with('user:id,name,avatar')
            ->latest()
            ->get();

        return Helper::JsonResponse(true, 'Ratings fetched successfully', 200, $ratings);
    }

    /**
     * Store or update a user's rating and review for a course.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id', 
            'rating' => 'required|numeric|between:1,5',
            'message' => 'nullable|string|max:1000',
        ]);

        // Check if the user is subscribed to the course
        $isSubscribed = UserSubscription::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->exists();

        if (!$isSubscribed) {
            return Helper::JsonResponse(false, 'You must be subscribed to this course to leave a rating.', 403);
        }

        $rating = CourseRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $request->course_id,
            ],
            [
                'rating' => $request->rating,
                'message' => $request->message,
                'status' => true,
            ]
        );

        return Helper::JsonResponse(true, 'Rating submitted successfully', 200, $rating);
    }

    /**
     * Delete a rating/review. Only the owner can delete their review.
     */
    public function delete($id)
    {
        $rating = CourseRating::find($id);

        if (!$rating) {
            return Helper::JsonResponse(false, 'Rating not found.', 404);
        }

        if ($rating->user_id !== auth()->id()) {
            return Helper::JsonResponse(false, 'Unauthorized to delete this rating.', 403);
        }

        $rating->delete();

        return Helper::JsonResponse(true, 'Rating deleted successfully', 200);
    }
}
