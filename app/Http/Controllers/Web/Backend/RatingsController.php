<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RatingsController extends Controller
{
    /**
     * Display a listing of ratings and reviews.
     */
    public function index(Request $request)
    {
        // $data = CourseRating::with(['course:id,name', 'user:id,name'])->latest()->get();
        // dd($data);
        if ($request->ajax()) {
            $query = CourseRating::with(['course:id,name', 'user:id,name'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('course', function ($data) {
                    if ($data->course) {
                        $data->course->setAppends([]);
                    }
                    return $data->course ? $data->course->name : 'N/A';
                })
                ->addColumn('user', function ($data) {
                    if ($data->user) {
                        $data->user->setAppends([]);
                    }
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->filterColumn('course', function ($query, $keyword) {
                    $query->whereHas('course', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('user', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('course', function ($query, $order) {
                    $query->orderBy(Course::select('name')->whereColumn('courses.id', 'course_ratings.course_id'), $order);
                })
                ->orderColumn('user', function ($query, $order) {
                    $query->orderBy(User::select('name')->whereColumn('users.id', 'course_ratings.user_id'), $order);
                })
                ->addColumn('rating', function ($data) {
                    // Render a beautiful, premium numeric rating with a gold star badge
                    return '<span class="px-2.5 py-1 rounded bg-amber-50 text-amber-600 font-bold border border-amber-100 flex items-center gap-1 w-max">
                        <svg class="w-3.5 h-3.5 fill-amber-500 stroke-none" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        ' . number_format($data->rating, 1) . '
                    </span>';
                })
                ->addColumn('message', function ($data) {   
                    if(strlen($data->message) > 50) {
                        return '<span class="text-slate-500">' . substr($data->message, 0, 50) . '...</span>';
                    } else {
                        return '<span class="text-slate-400 italic">'. $data->message .'</span>';

                    }
                })  
                ->addColumn('status', function ($data) {
                    $status = '<label class="inline-flex items-center cursor-pointer">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" value="" class="sr-only peer" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status) {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '<div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>';
                    $status .= '</label>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $encodedData = htmlspecialchars(json_encode([
                        'user' => $data->user ? $data->user->name : 'N/A',
                        'course' => $data->course ? $data->course->name : 'N/A',
                        'message' => $data->message,
                        'rating' => number_format($data->rating, 1)
                    ]), ENT_QUOTES, 'UTF-8');

                        return '<div role="group" style="gap: 10px;display: flex; justify-content: center; text-align: center">
                            <a href="#!" onclick="showReviewModal(this)" data-info=\'' . $encodedData . '\' class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" title="View Details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200" title="Delete Review">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                            </a>
                        </div>';
                })
                ->rawColumns(['rating', 'status', 'message', 'action'])
                ->make(true);
        }

        return view('backend.layout.ratings.index');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy($id)
    {
        try {
            $rating = CourseRating::findOrFail($id);
            $rating->delete();
            return response()->json(['success' => true, 'message' => 'Review deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Toggle status (active/inactive) for a rating.
     */
    public function status($id)
    {
        try {
            $rating = CourseRating::findOrFail($id);
            $rating->status = !$rating->status;
            $rating->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
